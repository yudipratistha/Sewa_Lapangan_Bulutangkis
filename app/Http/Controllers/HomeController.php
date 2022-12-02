<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Jobs\TelegramBotJob;
use Illuminate\Http\Request;
use App\Models\StatusLapangan;
use App\Models\TipeStatusCourt;
use App\Jobs\TelegramSenderBotJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function administratorHome(){
        return view('admin.admin_dashboard');
    }

    public function pemilikLapanganHome(){
        $dataLapangan = DB::table('tb_courts')->select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam',
            'tb_lapangan.jumlah_court', 'tb_courts.nomor_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->where('id_pengguna', Auth::user()->id)
            ->where('tb_courts.status_court', '!=', 0)
            ->get();

        $dataTipeStatusCourt = TipeStatusCourt::select('tb_tipe_status_court.id AS tipe_status_court_id', 'tb_tipe_status_court.tipe_status')->get();

        return view('pemilik_lapangan.pemilik_lapangan_dashboard', compact('dataLapangan', 'dataTipeStatusCourt'));
    }


    public function penyewaLapanganHome(){
        return view('penyewa_lapangan.penyewa_lapangan_dashboard');
    }
}
