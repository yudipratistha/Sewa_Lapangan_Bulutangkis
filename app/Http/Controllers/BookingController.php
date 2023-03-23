<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Kupon;
use App\Models\Pesan;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;

use App\Jobs\TelegramBotJob;
use Illuminate\Http\Request;
use App\Models\DetailBooking;

use App\Models\StatusLapangan;
use App\Jobs\TelegramSenderBotJob;
use Illuminate\Support\Facades\DB;
use App\Events\PembayaranLimitTime;
use App\Jobs\PembayaranLimitTimeJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use App\Models\RiwayatStatusPembayaran;
use App\Services\Midtrans\CreateSnapTokenService;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeBookingLapanganPerJam(Request $request){
        $currentDate = date('Y-m-d');
        $errorTextJamBooking = '';
        $errorTextPembayaran = '';
        $totalOrderNormal = 0;
        $totalOrderPromo = 0;
        $totalHargaBookingLapanganNormal = 0;
        $totalHargaBookingLapanganPromo = 0;
        $totalHargaBookingLapangan = 0;
        $bookingCounter = 0;

        if($request->tglBooking >= $currentDate){
            $dataBookArr = array();

            $dataLapangan = DB::table('tb_courts')->select('tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->where('tb_lapangan.id', $request->lapanganId)
                ->first();

            $kupons= Kupon::where('id_lapangan', $request->lapanganId)
                ->where('kode_kupon', $request->kode_kupon)
                ->whereRaw('tb_kupon.`tgl_berlaku_dari` <= "'.date('Y-m-d').'" AND tb_kupon.`tgl_berlaku_sampai` >= "'.date('Y-m-d').'"')
                ->first();

            if($dataLapangan->status_pembayaran === 'Belum Lunas'){
                return response()->json(['error' => "Ada data pembayaran belum lunas"], 400);
            }

            if(isset($request->orderData) && $request->pilihPembayaran !== "undefined"){
                foreach($request->orderData as $orderDataDate => $orderDataCourt){
                    $dataHargaLapangan = DB::table('tb_courts')->select('harga_normal', 'harga_promo', 'tgl_harga_normal_perjam_berlaku_mulai', 'tgl_promo_perjam_berlaku_dari',
                        'tgl_promo_perjam_berlaku_sampai')
                        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                        ->leftJoin('tb_harga_sewa_perjam_normal', function($join) use ($orderDataDate){
                            $join->on('tb_harga_sewa_perjam_normal.id_lapangan', '=', 'tb_lapangan.id')
                            ->whereRaw('tb_harga_sewa_perjam_normal.id IN (SELECT MAX(tb_harga_sewa_perjam_normal.id) FROM tb_harga_sewa_perjam_normal
                                WHERE tb_harga_sewa_perjam_normal.`tgl_harga_normal_perjam_berlaku_mulai` <= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_normal.status_delete = 0 GROUP BY tb_harga_sewa_perjam_normal.id_lapangan)');
                        })
                        ->leftJoin('tb_harga_sewa_perjam_promo', function($join) use ($orderDataDate){
                            $join->on('tb_harga_sewa_perjam_promo.id_lapangan', '=', 'tb_lapangan.id')
                            ->whereRaw('tb_harga_sewa_perjam_promo.id IN (SELECT MAX(tb_harga_sewa_perjam_promo.id) FROM tb_harga_sewa_perjam_promo
                                WHERE tb_harga_sewa_perjam_promo.`tgl_promo_perjam_berlaku_dari` <= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_promo.`tgl_promo_perjam_berlaku_sampai` >= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_promo.status_delete = 0 GROUP BY tb_harga_sewa_perjam_promo.id_lapangan)');
                        })
                        ->where('tb_lapangan.id', $request->lapanganId)
                        ->first();

                    foreach($orderDataCourt as $courtKey => $orderDataVal){
                        foreach($orderDataVal as $bookingDataKey => $bookingDataVal){
                            $jam = explode(" - ", $bookingDataVal['jam']);
                            if($dataHargaLapangan->tgl_promo_perjam_berlaku_dari <= $orderDataDate && $dataHargaLapangan->tgl_promo_perjam_berlaku_sampai >= $orderDataDate){
                                $totalOrderPromo = count($request->orderData[$orderDataDate][$courtKey]);
                                $totalHargaBookingLapanganPromo += $dataHargaLapangan->harga_promo;

                                array_push($dataBookArr, array(
                                    'jam_mulai' => $jam[0],
                                    'jam_selesai' => $jam[1],
                                    'harga_per_jam' => $dataHargaLapangan->harga_promo
                                ));
                            }else if($dataHargaLapangan->tgl_harga_normal_perjam_berlaku_mulai <= $orderDataDate){
                                $totalOrderNormal = count($request->orderData[$orderDataDate][$courtKey]);
                                $totalHargaBookingLapanganNormal += $dataHargaLapangan->harga_normal;

                                array_push($dataBookArr, array(
                                    'jam_mulai' => $jam[0],
                                    'jam_selesai' => $jam[1],
                                    'harga_per_jam' => $dataHargaLapangan->harga_normal
                                ));
                            }
                        }
                    }
                }

                $totalHargaBookingLapangan = $totalHargaBookingLapanganNormal + $totalHargaBookingLapanganPromo;

                $pembayaran = new Pembayaran;
                $pembayaran->id_daftar_jenis_pembayaran = $request->pilihPembayaran;
                $pembayaran->jenis_booking = 'per_jam';
                $pembayaran->total_biaya = $totalHargaBookingLapangan;
                $pembayaran->total_biaya_diskon = $totalHargaBookingLapangan - $kupons->total_diskon_persen;
                $pembayaran->total_diskon_persen = $kupons->total_diskon_persen;
                $pembayaran->kode_kupon = $kupons->kode_kupon;
                $pembayaran->save();

                $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
                $riwayatStatusPembayaran->id_pembayaran = $pembayaran->id;
                $riwayatStatusPembayaran->status_pembayaran = 'Belum Lunas';
                $riwayatStatusPembayaran->save();

                // PembayaranLimitTimeJob::dispatch($pembayaran)->onConnection('paymentConnection');

                foreach($request->orderData as $orderDataDate => $orderDataCourt){
                    $dataHargaLapangan = DB::table('tb_courts')->select('harga_normal', 'harga_promo', 'tgl_harga_normal_perjam_berlaku_mulai', 'tgl_promo_perjam_berlaku_dari',
                        'tgl_promo_perjam_berlaku_sampai')
                        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                        ->leftJoin('tb_harga_sewa_perjam_normal', function($join) use ($orderDataDate){
                            $join->on('tb_harga_sewa_perjam_normal.id_lapangan', '=', 'tb_lapangan.id')
                            ->whereRaw('tb_harga_sewa_perjam_normal.id IN (SELECT MAX(tb_harga_sewa_perjam_normal.id) FROM tb_harga_sewa_perjam_normal
                                WHERE tb_harga_sewa_perjam_normal.`tgl_harga_normal_perjam_berlaku_mulai` <= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_normal.status_delete = 0 GROUP BY tb_harga_sewa_perjam_normal.id_lapangan)');
                        })
                        ->leftJoin('tb_harga_sewa_perjam_promo', function($join) use ($orderDataDate){
                            $join->on('tb_harga_sewa_perjam_promo.id_lapangan', '=', 'tb_lapangan.id')
                            ->whereRaw('tb_harga_sewa_perjam_promo.id IN (SELECT MAX(tb_harga_sewa_perjam_promo.id) FROM tb_harga_sewa_perjam_promo
                                WHERE tb_harga_sewa_perjam_promo.`tgl_promo_perjam_berlaku_dari` <= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_promo.`tgl_promo_perjam_berlaku_sampai` >= "'.$orderDataDate.'" AND tb_harga_sewa_perjam_promo.status_delete = 0)');
                        })
                        ->where('tb_lapangan.id', $request->lapanganId)
                        ->first();
                    foreach($orderDataCourt as $courtKey => $orderDataVal){
                        $dataCourt = DB::table('tb_courts')->select('id AS court_id')->where('id_lapangan', $request->lapanganId)->where('nomor_court', $courtKey)->first();
                        $booking = new Booking();
                        $booking->id_pengguna = Auth::user()->id;
                        $booking->id_pembayaran = $pembayaran->id;
                        $booking->id_court = $dataCourt->court_id;
                        $booking->tgl_booking = date('Y-m-d', strtotime($orderDataDate));
                        $booking->save();

                        foreach($orderDataVal as $bookingDataKey => $bookingDataVal){
                            if($dataHargaLapangan->tgl_promo_perjam_berlaku_dari <= $orderDataDate && $dataHargaLapangan->tgl_promo_perjam_berlaku_sampai >= $orderDataDate){
                                $dataBookArr[$bookingCounter]['id_booking'] = $booking->id;
                            }else if($dataHargaLapangan->tgl_harga_normal_perjam_berlaku_mulai <= $orderDataDate){
                                $dataBookArr[$bookingCounter]['id_booking'] = $booking->id;
                            }
                            $bookingCounter++;
                        }
                    }
                }

                DetailBooking::insert($dataBookArr);

                $chatIdLapangan = DB::table('tb_pengguna')->select('tb_pengguna.chat_id')
                                    ->leftJoin('tb_lapangan', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
                                    ->where('tb_lapangan.id', $request->lapanganId)
                                    ->first();

                $dataPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.name', 'tb_pengguna.chat_id AS pengguna_chat_id')
                                    ->where('tb_pengguna.id', Auth::user()->id)
                                    ->first();

                if(isset($chatIdLapangan)){
                    $pesanToPemilik = new Pesan;
                    $pesanToPemilik->chat_id = $chatIdLapangan->chat_id;
                    $pesanToPemilik->pesan = 'Terdapat transaksi penyewaan baru atas nama '. $dataPenyewa->name .' pada tanggal '. date('d-m-Y') .'. Berikut link rincian penyewaan <a href="'. rawurlencode('https://'.$_SERVER['SERVER_NAME'].'/pemilik-lapangan/dashboard?tanggalSewa='.$request->tglBooking.'&penggunaPenyewaId='.Auth::user()->id.'&court=1&pembayaranId='.$pembayaran->id) .'">klik disini</a>. Mohon untuk diperiksa. Terima kasih!';
                    $pesanToPemilik->save();

                    $pesanToPengguna = new Pesan;
                    $pesanToPengguna->chat_id = $dataPenyewa->pengguna_chat_id;
                    $pesanToPengguna->pesan = 'Hi '.$dataPenyewa->name .', mohon segera lunasi transaksi anda pada tanggal '. date('d-m-Y') .'. Anda miliki waktu kurang dari 15 menit untuk melunasi transaksi anda. Berikut link transaksi penyewaan <a href="'. rawurlencode('https://'.$_SERVER['SERVER_NAME'].'/penyewa-lapangan/menunggu-pembayaran') .'">klik disini</a>. Terima kasih!';
                    $pesanToPengguna->save();

                    PembayaranLimitTimeJob::dispatch($pembayaran, $pesanToPengguna)->onConnection('paymentConnection');
                    TelegramSenderBotJob::dispatch($pesanToPemilik)->onConnection('telegramSenderBotConnection');
                }

                return response()->json('success');
            }

            if(!isset($request->orderData)){
                $errorTextJamBooking = "Jam booking belum dipilih!";
            }
            if($request->pilihPembayaran === "undefined"){
                $errorTextPembayaran = "Pilih pembayaran belum dipilih!";
            }

            if(isset($errorTextJamBooking) || isset($errorTextPembayaran)){
                return response()->json(['errorTextJamBooking' => $errorTextJamBooking, 'errorTextPembayaran' => $errorTextPembayaran], 400);
            }
        }
    }

    public function storeBookingLapanganBulanan(Request $request){
        $currentDate = date('d-m-Y');
        $errorTextJamBooking = '';
        $errorTextPembayaran = '';
        // dd($request->orderData);
        // if(!isset($request->checkBook)) dd($request->checkBook);

        if($request->tglBooking >= $currentDate){
            $dataBookArr = array();

            $dataLapangan = DB::table('tb_courts')->select('tb_riwayat_status_pembayaran.status_pembayaran',
                'tb_paket_sewa_bulanan_normal.harga_normal', 'tb_paket_sewa_bulanan_normal.total_durasi_jam_normal',
                'tb_paket_sewa_bulanan_promo.harga_promo', 'tb_paket_sewa_bulanan_promo.total_durasi_jam_promo')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->leftJoin('tb_paket_sewa_bulanan_normal', function($join) use ($request) {
                    $join->on('tb_paket_sewa_bulanan_normal.id_lapangan', '=', 'tb_lapangan.id')
                    ->whereRaw('tb_paket_sewa_bulanan_normal.id IN (SELECT MAX(tb_paket_sewa_bulanan_normal.id) FROM tb_paket_sewa_bulanan_normal
                        WHERE tb_paket_sewa_bulanan_normal.`tgl_harga_normal_bulanan_berlaku_mulai` <= "'.$request->tglBooking.'" AND tb_paket_sewa_bulanan_normal.status_delete = 0)');
                })
                ->leftJoin('tb_paket_sewa_bulanan_promo', function($join) use ($request) {
                    $join->on('tb_paket_sewa_bulanan_promo.id_lapangan', '=', 'tb_lapangan.id')
                    ->whereRaw('tb_paket_sewa_bulanan_promo.id IN (SELECT MAX(tb_paket_sewa_bulanan_promo.id) FROM tb_paket_sewa_bulanan_promo
                        WHERE tb_paket_sewa_bulanan_promo.`tgl_promo_paket_bulanan_berlaku_dari` <= "'.$request->tglBooking.'" AND tb_paket_sewa_bulanan_promo.`tgl_promo_paket_bulanan_berlaku_sampai` >= "'.$request->tglBooking.'" AND tb_paket_sewa_bulanan_promo.status_delete = 0)');
                })
                ->where('tb_lapangan.id', $request->lapanganId)
                ->first();

            $kupons= Kupon::where('id_lapangan', $request->lapanganId)
                ->where('kode_kupon', $request->kode_kupon)
                ->whereRaw('tb_kupon.`tgl_berlaku_dari` <= "'.date('Y-m-d').'" AND tb_kupon.`tgl_berlaku_sampai` >= "'.date('Y-m-d').'"')
                ->first();

            if($dataLapangan->status_pembayaran === 'Belum Lunas'){
                return response()->json(['error' => "Ada data pembayaran belum lunas"], 400);
            }

            if(isset($request->orderData) && $request->pilihPembayaran !== "undefined"){
                $pembayaran = new Pembayaran;
                $pembayaran->id_daftar_jenis_pembayaran = $request->pilihPembayaran;
                $pembayaran->jenis_booking = 'bulanan';
                $pembayaran->total_biaya = (isset($dataLapangan->harga_promo) ? $dataLapangan->harga_promo : $dataLapangan->harga_normal);
                $pembayaran->total_biaya_diskon = (isset($dataLapangan->harga_promo) ? $dataLapangan->harga_promo - $kupons->total_diskon_persen : $dataLapangan->harga_normal - $kupons->total_diskon_persen);
                $pembayaran->total_diskon_persen = $kupons->total_diskon_persen;
                $pembayaran->kode_kupon = $kupons->kode_kupon;
                $pembayaran->save();

                $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
                $riwayatStatusPembayaran->id_pembayaran = $pembayaran->id;
                $riwayatStatusPembayaran->status_pembayaran = 'Belum Lunas';
                $riwayatStatusPembayaran->save();

                foreach($request->orderData as $orderDataDate => $orderDataCourt){
                    foreach($orderDataCourt as $courtKey => $orderDataVal){
                        $dataCourt = DB::table('tb_courts')->select('id AS court_id')->where('id_lapangan', $request->lapanganId)->where('nomor_court', $courtKey)->first();
                        $booking = new Booking();
                        $booking->id_pengguna = Auth::user()->id;
                        $booking->id_pembayaran = $pembayaran->id;
                        $booking->id_court = $dataCourt->court_id;
                        $booking->tgl_booking = date('Y-m-d', strtotime($orderDataDate));
                        $booking->save();
                        foreach($orderDataVal as $bookingDataKey => $bookingDataVal){
                            $jam = explode(" - ", $bookingDataVal['jam']);
                            array_push($dataBookArr, array(
                                'id_booking' => $booking->id,
                                'jam_mulai' => $jam[0],
                                'jam_selesai' => $jam[1]
                            ));
                        }
                    }
                }
                DetailBooking::insert($dataBookArr);

                $chatIdLapangan = DB::table('tb_pengguna')->select('tb_pengguna.chat_id')
                                    ->leftJoin('tb_lapangan', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
                                    ->where('tb_lapangan.id', $request->lapanganId)
                                    ->first();

                $namaPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.name')
                                    ->where('tb_pengguna.id', Auth::user()->id)
                                    ->first();

                $pesan = new Pesan;
                $pesan->chat_id = $chatIdLapangan->chat_id;
                $pesan->pesan = 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa->name .' pada tanggal '. date('d-m-Y', strtotime($request->tglBooking)) .'. Berikut link rincian penyewaan <a href="'. rawurlencode('https://'.$_SERVER['SERVER_NAME'].'/pemilik-lapangan/dashboard?tanggalSewa='.$request->tglBooking.'&penggunaPenyewaId='.Auth::user()->id.'&court=1&pembayaranId='.$pembayaran->id) .'">klik disini</a>. Mohon untuk diperiksa. Terima kasih!';
                $pesan->save();

                PembayaranLimitTimeJob::dispatch($pembayaran, $pesan);
                TelegramSenderBotJob::dispatch($pesan)->onConnection('telegramSenderBotConnection');

                return response()->json('success');
            }

            if(!isset($request->orderData)){
                $errorTextJamBooking = "Jam booking belum dipilih!";
            }
            if($request->pilihPembayaran === "undefined"){
                $errorTextPembayaran = "Pilih pembayaran belum dipilih!";
            }

            if(isset($errorTextJamBooking) || isset($errorTextPembayaran)){
                return response()->json(['errorTextJamBooking' => $errorTextJamBooking, 'errorTextPembayaran' => $errorTextPembayaran], 400);
            }
        }
    }
}
