<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
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
        $currentDate = date('d-m-Y');
        $errorTextJamBooking = '';
        $errorTextPembayaran = '';
        $totalOrder = 0;
        // if(!isset($request->checkBook)) dd($request->checkBook);

        if($request->tglBooking >= $currentDate){
            $dataBookArr = array();

            $dataLapangan = DB::table('tb_courts')->select('tb_lapangan.harga_per_jam', 'tb_riwayat_status_pembayaran.status_pembayaran')
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

            if($dataLapangan->status_pembayaran === 'Belum Lunas'){
                return response()->json(['error' => "Ada data pembayaran belum lunas"], 400);
            }

            if(isset($request->orderData) && $request->pilihPembayaran !== "undefined"){
                foreach($request->orderData as $orderDataDate => $orderDataCourt){
                    foreach($orderDataCourt as $courtKey => $orderDataVal){
                        $totalOrder += count($request->orderData[$orderDataDate][$courtKey]);
                        $totalHargaBookingLapangan =  $totalOrder * $dataLapangan->harga_per_jam;
                    }
                }

                $pembayaran = new Pembayaran;
                $pembayaran->id_daftar_jenis_pembayaran = $request->pilihPembayaran;
                $pembayaran->jenis_booking = 'per_jam';
                $pembayaran->total_biaya = $totalHargaBookingLapangan;
                $pembayaran->save();

                $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
                $riwayatStatusPembayaran->id_pembayaran = $pembayaran->id;
                $riwayatStatusPembayaran->status_pembayaran = 'Belum Lunas';
                $riwayatStatusPembayaran->save();

                PembayaranLimitTimeJob::dispatch($pembayaran)->onConnection('paymentConnection');

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
                                'jam_selesai' => $jam[1],
                                'harga_per_jam' => $dataLapangan->harga_per_jam
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
                if(isset($chatIdLapangan)){
                    $pesan = new Pesan;
                    $pesan->chat_id = $chatIdLapangan->chat_id;
                    $pesan->pesan = 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa->name .' pada tanggal '. $request->tglBooking .'. Mohon untuk diperiksa. Terima kasih!';
                    $pesan->save();

                    // DB::insert('insert into tb_pesan (chat_id, pesan) values (?, ?)', [$chatIdLapangan[0]->chat_id, 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa[0]->name .' pada tanggal '. $request->tglBooking .'. Mohon untuk diperiksa. Terima kasih!']);

                    TelegramSenderBotJob::dispatch($pesan)->onConnection('telegramSenderBotConnection');
                }



                return response()->json($chatIdLapangan);
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

            $dataLapangan = DB::table('tb_courts')->select('tb_lapangan.harga_per_jam', 'tb_paket_sewa_bulanan.total_durasi_jam',
                'tb_paket_sewa_bulanan.total_harga AS total_harga_paket_bulanan', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_paket_sewa_bulanan', 'tb_paket_sewa_bulanan.id_lapangan', '=', 'tb_lapangan.id')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->where('tb_lapangan.id', $request->lapanganId)
                ->first();

            if($dataLapangan->status_pembayaran === 'Belum Lunas'){
                return response()->json(['error' => "Ada data pembayaran belum lunas"], 400);
            }

            if(isset($request->orderData) && $request->pilihPembayaran !== "undefined"){
                $pembayaran = new Pembayaran;
                $pembayaran->id_daftar_jenis_pembayaran = $request->pilihPembayaran;
                $pembayaran->jenis_booking = 'bulanan';
                $pembayaran->total_biaya = $dataLapangan->total_harga_paket_bulanan;
                $pembayaran->save();

                $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
                $riwayatStatusPembayaran->id_pembayaran = $pembayaran->id;
                $riwayatStatusPembayaran->status_pembayaran = 'Belum Lunas';
                $riwayatStatusPembayaran->save();

                PembayaranLimitTimeJob::dispatch($pembayaran);

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
                $pesan->pesan = 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa->name .' pada tanggal '. $request->tglBooking .'. Mohon untuk diperiksa. Terima kasih!';
                $pesan->save();

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
