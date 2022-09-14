<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\RiwayatStatusPembayaran;
use App\Services\Midtrans\CreateSnapTokenService; 

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
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')  
        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_pembayaran.id_daftar_jenis_pembayaran', '=', 'tb_daftar_jenis_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
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
        
        $limitWaktuUploadBuktiTrx = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime(isset($dataMenungguPembayaran) ? $dataMenungguPembayaran->pembayaran_created_at : '')));

        return view('penyewa_lapangan.penyewa_lapangan_menunggu_pembayaran', compact('dataMenungguPembayaran', 'limitWaktuUploadBuktiTrx'));
    }

    public function getPembayaranDetail(){
        $waktuBook = '';
        $totalCourt= '';
        $counter = 0;

        $pembayaranDetail = DB::table('tb_booking')->select('tb_pengguna.name', 'tb_booking.tgl_booking', 'tb_booking.court', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 
        'tb_pembayaran.total_biaya')
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')  
        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
        })
        ->where('tb_booking.id_pengguna', Auth::user()->id)
        ->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas')
        ->groupBy('tb_booking.id')
        ->get();

        foreach($pembayaranDetail as $pembayaranDetailKey => $pembayaranDetailValue){
            $punctuation = '';
            if(count($pembayaranDetail) > $counter+1 && count($pembayaranDetail) === 2){
                $punctuation= ' & ';
            }else if(count($pembayaranDetail) >= $counter+1 && count($pembayaranDetail)-3 !== $counter-1 && count($pembayaranDetail) !== $counter+1){
                $punctuation= ', ';
            }else if(count($pembayaranDetail) >= 2 && count($pembayaranDetail)-3 === $counter-1){
                $punctuation= ' & ';
            }
            $waktuBook .= date('H:i', strtotime($pembayaranDetailValue->jam_mulai)) .'-'. date('H:i', strtotime($pembayaranDetailValue->jam_selesai)) . $punctuation;
            $totalCourt .= $pembayaranDetailValue->court;

            $counter++;
        }
        $totalCourt = preg_replace('/(.)\\1+/', '$1', $totalCourt);
        $totalCourt = implode(', ', str_split($totalCourt));
        $totalCourt = preg_replace('/,([^,]*)$/', ' &$1', $totalCourt);

        $dataPembayaranDetailArr = array(
            "nama_penyewa" => $pembayaranDetail[0]->name,
            "tgl_penyewaan" => date("d-m-Y", strtotime($pembayaranDetail[0]->tgl_booking)),
            "waktu_book" => $waktuBook,
            "total_court" => $totalCourt,
            "total_biaya" => $pembayaranDetail[0]->total_biaya
        );

        return response()->json($dataPembayaranDetailArr);
    }

    public function simpanBuktiPembayaran(Request $request){
        if($request->hasFile('foto_bukti_bayar')){
            $dataPembayaran = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_pembayaran.id AS pembayaran_id', 
            'tb_pembayaran.created_at AS pembayaran_created_at')
                ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')  
                ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
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

            return response()->json('success');
        }else{
            return response()->json(['error_bukti_trx' => "Foto bukti transfer kosong!"], 400);
        }
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

        return response()->json('success');
    }

}
