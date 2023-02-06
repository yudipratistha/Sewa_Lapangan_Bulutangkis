<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pesan;
use App\Models\Pembayaran;
use App\Models\DaftarJenisPembayaran;
use App\Models\RiwayatStatusPembayaran;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Jobs\TelegramBotJob;
use App\Jobs\TelegramSenderBotJob;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PembayaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listPaymentMethodPemilikLapangan(){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataDaftarJenisPembayaranLapangan = DB::table('tb_lapangan')->select('tb_daftar_jenis_pembayaran.id AS daftar_jenis_pembayaran_id', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama',
            'tb_daftar_jenis_pembayaran.no_rekening', 'tb_daftar_jenis_pembayaran.status_delete')
            ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_lapangan.id', $lapanganId->id)
            ->get();

        return view('pemilik_lapangan.pemilik_lapangan_list_payment_method', compact('lapanganId', 'dataDaftarJenisPembayaranLapangan'));
    }

    public function updatePaymentMethodPemilikLapangan(Request $request){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        for($counter= 0; $counter < count($request->nama_metode_pembayaran); $counter++){
            DaftarJenisPembayaran::updateOrCreate([
                    'id' => isset($request->jenis_pembayaran_metode_id[$counter]) ? $request->jenis_pembayaran_metode_id[$counter] : null,
                ],[
                    'id_lapangan' => $lapanganId->id,
                    'nama_jenis_pembayaran' => $request->nama_metode_pembayaran[$counter],
                    'atas_nama' => $request->atas_nama[$counter],
                    'no_rekening' => $request->no_rek_virtual_account[$counter]
                ]
            );
        }
        return response()->json('success');
    }

    public function restorePaymentMethodPemilikLapangan(Request $request){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        DaftarJenisPembayaran::where('tb_daftar_jenis_pembayaran.id', $request->data_payment_method_id)
        ->where('tb_daftar_jenis_pembayaran.id_lapangan', $lapanganId->id)->update(['status_delete' => 0]);

        return response()->json('success');
    }

    public function destroyPaymentMethodPemilikLapangan(Request $request){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        DaftarJenisPembayaran::where('tb_daftar_jenis_pembayaran.id', $request->data_payment_method_id)
        ->where('tb_daftar_jenis_pembayaran.id_lapangan', $lapanganId->id)->update(['status_delete' => 1]);

        return response()->json('success');
    }

    public function getDaftarJenisPembayaran($idLapangan){
        $dataDaftarJenisPembayaranLapangan = DB::table('tb_lapangan')->select('tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama',
        'tb_daftar_jenis_pembayaran.no_rekening')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
        ->where('tb_lapangan.id', $idLapangan)
        ->get();

        return response()->json($dataDaftarJenisPembayaranLapangan);
    }

    public function menungguPembayaranPenyewaIndex(){
        $dataMenungguPembayaran = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.foto_lapangan_1', 'tb_pengguna.name',
        'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama', 'tb_daftar_jenis_pembayaran.no_rekening', 'tb_booking.tgl_booking', 'tb_pembayaran.id AS pembayaran_id',
        'tb_pembayaran.total_biaya', 'tb_pembayaran.created_at AS pembayaran_created_at')
        ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
        ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_pembayaran.id_daftar_jenis_pembayaran', '=', 'tb_daftar_jenis_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
        })
        ->where('tb_booking.id_pengguna', Auth::user()->id)
        ->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas')
        ->first();

        // $snapToken = $dataMenungguPembayaran->snap_token;
        // if (empty($snapToken)) {
        //     $pembayaran = Pembayaran::find($dataMenungguPembayaran->pembayaran_id);
        //     // Jika snap token masih NULL, buat token snap dan simpan ke database

        //     $midtrans = new CreateSnapTokenService($dataMenungguPembayaran->pembayaran_id);
        //     $snapToken = $midtrans->getSnapToken();
        //     // dd($snapToken);
        //     $pembayaran->snap_token = $snapToken;
        //     $pembayaran->save();
        // }
        // dd($dataMenungguPembayaran);
        $limitWaktuUploadBuktiTrx = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime(isset($dataMenungguPembayaran) ? $dataMenungguPembayaran->pembayaran_created_at : '')));

        return view('penyewa_lapangan.penyewa_lapangan_menunggu_pembayaran', compact('dataMenungguPembayaran', 'limitWaktuUploadBuktiTrx'));
    }

    public function getPembayaranDetail(){
        $dataPembayaran = DB::table('tb_booking')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_detail_booking.harga_per_jam',
            'tb_pengguna.name', 'tb_pembayaran.jenis_booking', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_pembayaran.total_biaya', 'tb_pembayaran.id AS pembayaran_id', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
            ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
            ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
            })
            ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id', '=', 'tb_pembayaran.id_daftar_jenis_pembayaran')
            ->where('tb_booking.id_pengguna', Auth::user()->id)->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas')
            ->get();

        $dataPenyewaLapanganInvoice = array();
        $counter = 0;

        foreach($dataPembayaran as $dataPembayaranIndex => $dataPembayaranValue){
            $dataPenyewaLapanganInvoice[$dataPembayaranValue->tgl_booking] = [];
        }

        for($countDate= 0; $countDate < count($dataPenyewaLapanganInvoice); $countDate++){
            foreach($dataPembayaran as $dataPembayaranIndex => $dataPembayaranValue){
                if(array_keys($dataPenyewaLapanganInvoice)[$countDate] === $dataPembayaranValue->tgl_booking){
                    $dataPenyewaLapanganInvoice[$dataPembayaranValue->tgl_booking][$counter] = $dataPembayaranValue;
                    $counter++;
                }else{
                    $counter = 0;
                }
            }
        }

        return response()->json($dataPenyewaLapanganInvoice);
    }

    public function simpanBuktiPembayaran(Request $request){
        if($request->hasFile('foto_bukti_bayar')){
            $dataPembayaran = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_pembayaran.id AS pembayaran_id',
            'tb_pembayaran.created_at AS pembayaran_created_at')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
                ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->where('tb_booking.id_pengguna', Auth::user()->id)
                ->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas')
                ->first();

            $updateFotoBuktiTrx = Pembayaran::find($dataPembayaran->pembayaran_id);
            $updateStatusPembayaran = new RiwayatStatusPembayaran;

            $lapanganPath = 'bukti_bayar/'.strtolower(str_replace(' ', '_', $dataPembayaran->nama_lapangan)).'/';
            Storage::disk('local')->makeDirectory($lapanganPath);
            $fotoBuktiTrxUserPath = $lapanganPath.str_replace(' ', '_', Auth::user()->id).'/'.$dataPembayaran->tgl_booking.'/'.$dataPembayaran->pembayaran_id;
            Storage::disk('local')->makeDirectory($fotoBuktiTrxUserPath);
            $fotoBuktiTrx = $request->file('foto_bukti_bayar')->storeAs(
                $fotoBuktiTrxUserPath, $dataPembayaran->pembayaran_id."_".date("d-m-Y_H;i;s", strtotime($dataPembayaran->pembayaran_created_at)).".jpg", 'local'
            );
            $updateFotoBuktiTrx->foto_bukti_pembayaran = '/storage/'.$fotoBuktiTrx;
            $updateFotoBuktiTrx->save();

            $updateStatusPembayaran->id_pembayaran = $dataPembayaran->pembayaran_id;
            $updateStatusPembayaran->status_pembayaran = 'Proses';
            $updateStatusPembayaran->save();

            $chatIdLapangan = DB::table('tb_pengguna')->select('tb_pengguna.chat_id')
                                    ->leftJoin('tb_lapangan', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
                                    ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_lapangan.id', '=', 'tb_daftar_jenis_pembayaran.id_lapangan')
                                    ->leftJoin('tb_pembayaran', 'tb_daftar_jenis_pembayaran.id', '=', 'tb_pembayaran.id_daftar_jenis_pembayaran')
                                    ->where('tb_pembayaran.id', $dataPembayaran->pembayaran_id)
                                    ->first();

            // $namaPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.name')
            //                     ->where('tb_pengguna.id', Auth::user()->id)
            //                     ->get();

            // DB::insert('insert into tb_pesan (chat_id, pesan) values (?, ?)', [$chatIdLapangan[0]->chat_id, 'Transaksi oleh '. $namaPenyewa[0]->name .' telah dibayar. Mohon untuk diperiksa kelengkapan pembayaran dan mengubah status. Terima kasih!']);

            // $chatIdLapangan = DB::table('tb_pengguna')->select('tb_pengguna.chat_id')
            //                         ->leftJoin('tb_lapangan', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            //                         ->where('tb_lapangan.id', $request->lapanganId)
            //                         ->first();

            $namaPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.name')
                                ->where('tb_pengguna.id', Auth::user()->id)
                                ->first();
            if(isset($chatIdLapangan)){
                $pesan = new Pesan;
                $pesan->chat_id = $chatIdLapangan->chat_id;
                $pesan->pesan = 'Transaksi oleh '. $namaPenyewa->name .' telah dibayar. Berikut link rincian penyewaan <a href="'. rawurlencode('http://'.$_SERVER['SERVER_NAME'].':8000/pemilik-lapangan/dashboard?tanggalSewa='.$request->tglBooking.'&penggunaPenyewaId='.Auth::user()->id.'&court=1&pembayaranId='.$dataPembayaran->pembayaran_id) .'">klik disini</a>. Mohon untuk diperiksa kelengkapan pembayaran dan mengubah status. Terima kasih!';
                $pesan->save();

                // DB::insert('insert into tb_pesan (chat_id, pesan) values (?, ?)', [$chatIdLapangan[0]->chat_id, 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa[0]->name .' pada tanggal '. $request->tglBooking .'. Mohon untuk diperiksa. Terima kasih!']);

                TelegramSenderBotJob::dispatch($pesan)->onConnection('telegramSenderBotConnection');
            }
            return response()->json('success');
        }

        return response()->json(['error_bukti_trx' => "Foto bukti transfer kosong!"], 400);
    }

    public function batalkanPembayaran(Request $request){
        $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
        $riwayatStatusPembayaran->id_pembayaran = $request->pembayaran_id;
        $riwayatStatusPembayaran->status_pembayaran = 'Batal';
        $riwayatStatusPembayaran->save();

        return response()->json('success');
    }

    public function updateStatusPembayaranPenyewa(Request $request){
        $dataPembayaran = Pembayaran::find($request->pembayaranId);

        $dataPembayaran->RiwayatStatusPembayaran()->insert(['id_pembayaran' => $request->pembayaranId,'status_pembayaran' => $request->statusPembayaran]);

        $chatIdPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.chat_id', 'tb_lapangan.nama_lapangan')
                                    ->leftJoin('tb_booking', 'tb_pengguna.id', '=', 'tb_booking.id_pengguna')
                                    ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                                    ->leftJoin('tb_courts', 'tb_booking.id_court', '=', 'tb_courts.id')
                                    ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                                    ->where('tb_pembayaran.id', $request->pembayaranId)
                                    ->first();

        $namaPenyewa = DB::table('tb_pengguna')->select('tb_pengguna.name')
                                    ->where('tb_pengguna.id', Auth::user()->id)
                                    ->first();

        if(isset($chatIdPenyewa)){
            $pesanToPemilik = new Pesan;
            $pesanToPemilik->chat_id = $chatIdPenyewa->chat_id;
            $pesanToPemilik->pesan = 'Pesanan penyewaan lapangan '. $chatIdPenyewa->nama_lapangan .' telah diupdate. Mohon untuk di periksa. Terima kasih!';
            $pesanToPemilik->save();

            // DB::insert('insert into tb_pesan (chat_id, pesan) values (?, ?)', [$chatIdLapangan[0]->chat_id, 'Terdapat transaksi penyewaan baru atas nama '. $namaPenyewa[0]->name .' pada tanggal '. $request->tglBooking .'. Mohon untuk diperiksa. Terima kasih!']);

            TelegramSenderBotJob::dispatch($pesanToPemilik)->onConnection('telegramSenderBotConnection');
        }

        // DB::insert('insert into tb_pesan (chat_id, pesan) values (?, ?)', [$chatIdPenyewa[0]->chat_id, 'Pesanan penyewaan lapangan '. $chatIdPenyewa[0]->nama_lapangan .' telah diupdate. Mohon untuk di periksa. Terima kasih!']);

        return response()->json('success');
    }

}
