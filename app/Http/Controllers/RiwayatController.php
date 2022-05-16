<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganRiwayatPenyewaan(){
        return view('pemilik_lapangan.pemilikLapanganRiwayat');
    }


    public function penyewaLapanganRiwayatPenyewaan(){
        return view('penyewa_lapangan.penyewaLapanganRiwayat');
    }
}
