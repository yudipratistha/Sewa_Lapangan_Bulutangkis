<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\RiwayatStatusPembayaran;

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
    
    public function menungguPembayaranPenyewaIndex(){
        return view('penyewa_lapangan.penyewa_lapangan_menunggu_pembayaran');
    }

    public function updateStatusPembayaranPenyewa(Request $request){
        $dataPembayaran = Pembayaran::find($request->pembayaranId);
        
        $dataPembayaran->RiwayatStatusPembayaran()->insert(['id_pembayaran' => $request->pembayaranId,'status_pembayaran' => $request->statusPembayaran]);

        return response()->json('success');
    }

}
