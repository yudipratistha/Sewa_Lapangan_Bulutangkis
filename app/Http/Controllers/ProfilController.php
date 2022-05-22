<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganProfil(){
        $dataProfilPemilikLapangan = Lapangan::with('User')->get();
        // echo '<pre>'; print_r($dataProfilPemilikLapangan->toArray());echo'<pre>';
        // die();
        $lapanganImage = array();
        if($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_1'] !== ""){
            $lapanganImage['foto_lapangan_1'] = 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_1'])));

        }else{
            $lapanganImage['foto_lapangan_1'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_2'] !== ""){
            $lapanganImage['foto_lapangan_2'] = 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_2'])));

        }else{
            $lapanganImage['foto_lapangan_2'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_3'] !== ""){
            $fileExtension = pathinfo(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_3']), PATHINFO_EXTENSION);
            $lapanganImage['foto_lapangan_3'] = 'data:image/'.$fileExtension.';base64,'.base64_encode(file_get_contents(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_3'])));

        }else{
            $lapanganImage['foto_lapangan_3'] = null;
        }
        // $response->header("Content-Type", 'image/jpg');
        // $image = $response;
        // dd($fileExtension);
        // die();
        return view('pemilik_lapangan.pemilikLapanganProfil', compact('dataProfilPemilikLapangan', 'lapanganImage'));
    }

    public function pemilikLapanganUpdateProfil(Request $request){
        

    }

    
    public function getPenyewaLapanganProfil($idPenggunaPenyewa){
        $dataProfilPenyewa = Booking::with('User')->where('id_pengguna', $idPenggunaPenyewa)->get();
        return response()->json($dataProfilPenyewa);
    }

    public function penyewaLapanganProfil(){
        return view('penyewa_lapangan.penyewaLapanganProfil');
    }
}
