<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganProfil(){
        $dataProfilPemilikLapangan = Lapangan::with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();
        // echo '<pre>'; print_r($dataProfilPemilikLapangan->toArray());echo'<pre>';
        // die();
        $lapanganImage = array();
        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_1'] !== ""){
            $lapanganImage['foto_lapangan_1'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_1'];

        }else{
            $lapanganImage['foto_lapangan_1'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_2'] !== ""){
            $lapanganImage['foto_lapangan_2'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_2'];

        }else{
            $lapanganImage['foto_lapangan_2'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_3'] !== ""){
            $fileExtension = pathinfo(storage_path($dataProfilPemilikLapangan->toArray()['foto_lapangan_3']), PATHINFO_EXTENSION);
            $lapanganImage['foto_lapangan_3'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_3'];

        }else{
            $lapanganImage['foto_lapangan_3'] = null;
        }
        // $response->header("Content-Type", 'image/jpg');
        // $image = $response;
        // dd($fileExtension);
        // die();
        return view('pemilik_lapangan.pemilik_lapangan_profil', compact('dataProfilPemilikLapangan', 'lapanganImage'));
    }

    public function pemilikLapanganUpdateProfil(Request $request){
        

    }

    
    public function getPenyewaLapanganProfil($penggunaPenyewaId, $date, $pembayaranId){
        $dataProfilPenyewa = DB::table('tb_pengguna')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 
            'tb_pengguna.id as pengguna_id', 'tb_pengguna.name', 'tb_pembayaran.total_biaya', 'tb_pembayaran.id AS pembayaran_id', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
            ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', $penggunaPenyewaId)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($date)))->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')->where('tb_booking.id_pembayaran', $pembayaranId)
            ->get();
        return response()->json($dataProfilPenyewa);
    }

    public function penyewaLapanganProfil(){
        $dataUser = User::select('name', 'email', 'nomor_telepon')->find(Auth::user()->id);
        
        return view('penyewa_lapangan.penyewa_lapangan_profil', compact('dataUser'));
    }
    
}
