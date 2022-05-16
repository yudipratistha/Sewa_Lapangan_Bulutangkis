<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganProfil(){
        return view('pemilik_lapangan.pemilikLapanganProfil');
    }

    
    public function penyewaLapanganProfil(){
        return view('penyewa_lapangan.penyewaLapanganProfil');
    }
}
