<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\StatusLapangan;

use Illuminate\Http\Request;
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
    public function index(){
        if(auth()->user()->user_status == 2){
            return redirect()->route('pemilikLapangan.dashboard');
        }else{
            return redirect()->route('home');
        }
        
    }

    public function pemilikLapanganHome(){
        $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->where('id_pengguna', Auth::user()->id)->get();

        $dataLapanganBooking = DB::table('tb_pengguna')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 'tb_booking.biaya', 'tb_booking.status', 
                        'tb_pengguna.id as pengguna_id', 'tb_pengguna.name')
                        ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
                        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                        ->where('tb_lapangan.id_pengguna', Auth::user()->id)->where('tb_booking.tgl_booking', '2022-05-19')
                        ->get();

        // $dataLapanganBooking = Lapangan::select('*')->with(['User' => function ($query) {
        //     $query->select('id', 'name');
        //     }, 'Booking.User'])->where('tb_lapangan.id_pengguna', Auth::user()->id)->get();

        $dataStatusLapangan = DB::table('tb_lapangan')->select('tb_status_lapangan.court', 'tb_status_lapangan.status', 'tb_status_lapangan.detail_status',
                    'tb_status_lapangan.jam_status_berlaku_dari', 'tb_status_lapangan.jam_status_berlaku_sampai')
                    ->leftJoin('tb_status_lapangan', 'tb_status_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                    ->where('tb_lapangan.id_pengguna', Auth::user()->id)
                    ->get();

        $dataWaktuLapangan = array();
        foreach($dataLapangan as $dataLapanganKey => $dataLapanganValue){
            $lapanganBuka = strtotime($dataLapanganValue->buka_dari_jam);
            $lapanganTutup = strtotime($dataLapanganValue->buka_sampai_jam);
            for($i=$lapanganBuka; $i<$lapanganTutup; $i+=3600) {
                $dataWaktuLapangan[$dataLapanganKey][] = date('H:i', $i) . " - ". date('H:i', $i+3600);
            }
        }
        // die();
        // dd($dataWaktuLapangan);
        return view('pemilik_lapangan.pemilik_lapangan_dashboard', compact('dataLapanganBooking', 'dataLapangan', 'dataWaktuLapangan', 'dataStatusLapangan'));
    }

    public function penyewaLapanganHome(){
        return view('penyewa_lapangan.penyewa_lapangan_dashboard');
    }
}
