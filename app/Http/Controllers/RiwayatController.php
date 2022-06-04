<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public function getDataRiwayatPenyewaLapangan(Request $request){
        // $dataBooking = Booking::where('id_pengguna', Auth::user()->id);

        
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        // $columnIndex = $columnIndex_arr[0]['column']; 
        // $columnName = $columnName_arr[$columnIndex]['data'];
        // $columnSortOrder = $order_arr[0]['dir'];
        // $searchValue = $search_arr['value'];

        $totalRecords = Booking::where('id_pengguna', Auth::user()->id)->count();

        $dataBooking = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court')
            ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->orderByRaw('tgl_booking DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
            
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $dataBooking
        );

        return response()->json($response);
    }
}
