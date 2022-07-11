<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\RiwayatStatusPembayaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganRiwayatPenyewaan(){
        return view('pemilik_lapangan.pemilik_lapangan_riwayat',);
    }

    public function getDataRiwayatPenyewaanPemilikLapangan(Request $request){
        $dataLapangan = Lapangan::select('tb_lapangan.id AS lapangan_id')->where('id_pengguna', Auth::user()->id)->first()->toArray();
        
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
        // dd($request->filterTanggalStart);
        $totalRecords = Booking::where('id_lapangan', $dataLapangan['lapangan_id'])
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->groupBy('tb_booking.id_pembayaran')
        ->get()->count();
        
        $dataBooking = DB::table('tb_booking')->select('tb_pembayaran.id AS id_pembayaran', 'tb_pengguna.name', 'tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 'tb_riwayat_status_pembayaran.status_pembayaran')
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')   
        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
        })
        ->where('tb_booking.id_lapangan', $dataLapangan['lapangan_id'])
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->groupBy('tb_pembayaran.id')
        ->orderByRaw('tb_pembayaran.id DESC')
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

        $dataBooking = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
                $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
            })
            ->groupBy('tb_pembayaran.id')
            ->orderByRaw('tb_pembayaran.id DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();
            // dd($dataBooking);
            
        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $dataBooking
        );

        return response()->json($response);
    }
}
