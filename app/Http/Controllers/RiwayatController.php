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
        return view('pemilik_lapangan.pemilik_lapangan_riwayat');
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
        $totalRecords = DB::table('tb_booking')->select('tb_pembayaran.id AS id_pembayaran', 'tb_pengguna.name', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
        ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
        ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
        })
        ->where('tb_courts.id_lapangan', $dataLapangan['lapangan_id'])
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
            if($request->filterStatusTrx === 'diproses'){
                $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Proses');
            }else if($request->filterStatusTrx === 'berhasil'){
                // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'DP');
                $query->whereRaw('tb_riwayat_status_pembayaran.status_pembayaran IN("Lunas", "DP")');
            }else if($request->filterStatusTrx === 'tidak berhasil'){
                $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Batal');
                // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas');
            }
        })
        ->groupBy('tb_pembayaran.id')
        ->get()->count();

        $dataBooking = DB::table('tb_booking')->select('tb_pengguna.id AS id_pengguna', 'tb_pembayaran.id AS id_pembayaran', 'tb_pengguna.name', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
        ->leftJoin('tb_pengguna', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
        ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
        ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
        })
        ->where('tb_courts.id_lapangan', $dataLapangan['lapangan_id'])
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
            if($request->filterStatusTrx === 'diproses'){
                $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Proses');
            }else if($request->filterStatusTrx === 'berhasil'){
                // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'DP');
                $query->whereRaw('tb_riwayat_status_pembayaran.status_pembayaran IN("Lunas", "DP")');
            }else if($request->filterStatusTrx === 'tidak berhasil'){
                $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Batal');
                // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas');
            }
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

    public function pemilikLapanganRiwayatTotalPemasukan(){
        return view('pemilik_lapangan.pemilik_lapangan_total_pemasukan');
    }

    public function getDataRiwayatTotalPemasukanPemilikLapangan(Request $request){
        (!isset($request->filterMonth) && !isset($request->filterYear)) ? $queryFilter = "YEAR(tb_pembayaran.created_at) = YEAR(NOW()) && MONTH(tb_pembayaran.created_at) = MONTH(NOW())" : $queryFilter = "YEAR(tb_pembayaran.created_at) = ".$request->filterYear." && MONTH(tb_pembayaran.created_at) = ". $request->filterMonth;

        $dataLapangan = Lapangan::with(['User' => function ($query) {$query->select('tb_pengguna.id AS lapangan_id', 'tb_pengguna.name', 'tb_pengguna.nomor_telepon'); }])
            ->select(['tb_lapangan.id as lapangan_id', 'tb_lapangan.id_pengguna', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_hari',
            'tb_lapangan.buka_sampai_hari', 'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'tb_lapangan.buka_dari_jam',
            'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court'])
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        $totalPemasukan = DB::select('
            SELECT
            CONCAT(DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK(tb_pembayaran.created_at), DAYNAME(DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY))), "%X%V %W"), "%d-%m-%Y"), " - ",
            DATE_FORMAT(STR_TO_DATE(CONCAT(YEARWEEK(tb_pembayaran.created_at), DAYNAME(DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY))), "%X%V %W") + INTERVAL 6 DAY, "%d-%m-%Y")) AS weekly_start_end ,
                COUNT(tb_pembayaran.id) AS total_transaksi, SUM(tb_pembayaran.`total_biaya`) AS value
            FROM (
                SELECT *
                FROM tb_booking
                GROUP BY tb_booking.`id_pembayaran`
            ) AS tb_booking
            LEFT JOIN tb_courts ON tb_courts.id = tb_booking.id_court
            LEFT JOIN tb_lapangan ON tb_lapangan.id = tb_courts.id_lapangan
            LEFT JOIN tb_pembayaran ON tb_booking.id_pembayaran = tb_pembayaran.id
            LEFT JOIN tb_riwayat_status_pembayaran ON tb_riwayat_status_pembayaran.`id_pembayaran` =  tb_pembayaran.id
                AND tb_riwayat_status_pembayaran.`id` IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)
            WHERE tb_courts.id_lapangan = '.$dataLapangan->lapangan_id.' && '.$queryFilter.' && (tb_riwayat_status_pembayaran.`status_pembayaran` != \'Batal\' &&
            tb_riwayat_status_pembayaran.`status_pembayaran` != \'Belum Lunas\' && tb_riwayat_status_pembayaran.`status_pembayaran` != \'Proses\')

            GROUP BY FROM_DAYS(TO_DAYS(tb_pembayaran.created_at) -MOD(TO_DAYS(tb_pembayaran.created_at) -DAYOFWEEK(DATE_SUB(CURRENT_DATE, INTERVAL DAYOFMONTH(CURRENT_DATE)-1 DAY)), 7))
        ');

        return response()->json($totalPemasukan);
    }

    public function pemilikLapanganRiwayatPenggunaBookingTerbanyak(){
        return view('pemilik_lapangan.pemilik_lapangan_pengguna_terbanyak');
    }

    public function getDataRiwayatPenggunaBookingTerbanyakPemilikLapangan(Request $request){
        (!isset($request->filterMonth) && !isset($request->filterYear)) ? $queryFilter = "YEAR(tb_pembayaran.created_at) = YEAR(NOW()) && MONTH(tb_pembayaran.created_at) = MONTH(NOW())" : $queryFilter = "YEAR(tb_pembayaran.created_at) = ".$request->filterYear." && MONTH(tb_pembayaran.created_at) = ". $request->filterMonth;

        $dataLapangan = Lapangan::with(['User' => function ($query) {$query->select('tb_pengguna.id AS lapangan_id', 'tb_pengguna.name', 'tb_pengguna.nomor_telepon'); }])
            ->select(['tb_lapangan.id as lapangan_id', 'tb_lapangan.id_pengguna', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_hari',
            'tb_lapangan.buka_sampai_hari', 'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'tb_lapangan.buka_dari_jam',
            'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court'])
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        $totalPenggunaBookingTerbanyak = DB::select('
            SELECT CONCAT(FROM_DAYS(TO_DAYS(tb_pembayaran.created_at) -MOD(TO_DAYS(tb_pembayaran.created_at) -1, 7)), \' - \',
            STR_TO_DATE(CONCAT(YEARWEEK(tb_pembayaran.created_at), \'Sunday\'), \'%X%V %W\') + INTERVAL 6 DAY) AS weekly_start_end, COUNT(tb_pengguna.`id`) AS total_booking, SUM(tb_pembayaran.`total_biaya`) AS value, tb_pengguna.`name` as name
            FROM (
                SELECT *
                FROM tb_booking
                GROUP BY tb_booking.`id_pembayaran`
            ) AS tb_booking
            LEFT JOIN tb_pengguna ON tb_pengguna.id = tb_booking.`id_pengguna`
            LEFT JOIN tb_courts ON tb_courts.id = tb_booking.id_court
            LEFT JOIN tb_lapangan ON tb_lapangan.id = tb_courts.id_lapangan
            LEFT JOIN tb_pembayaran ON tb_booking.id_pembayaran = tb_pembayaran.id
            LEFT JOIN tb_riwayat_status_pembayaran ON tb_riwayat_status_pembayaran.`id_pembayaran` =  tb_pembayaran.id
            AND tb_riwayat_status_pembayaran.`id` IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)
            WHERE tb_courts.id_lapangan = '.$dataLapangan->lapangan_id.' && '.$queryFilter.' && (tb_riwayat_status_pembayaran.`status_pembayaran` != \'Batal\' &&
            tb_riwayat_status_pembayaran.`status_pembayaran` != \'Belum Lunas\' && tb_riwayat_status_pembayaran.`status_pembayaran` != \'Proses\')

            GROUP BY tb_pengguna.id
            ORDER BY VALUE DESC
            LIMIT 5
        ');

        return response()->json($totalPenggunaBookingTerbanyak);
    }

    public function pemilikLapanganRiwayatBookingJamTerbanyak(Request $request){
        return view('pemilik_lapangan.pemilik_lapangan_booking_jam_terbanyak');
    }

    public function getDataRiwayatBookingJamTerbanyakPemilikLapangan(Request $request){

    }

    public function penyewaLapanganRiwayatPenyewaan(){
        return view('penyewa_lapangan.penyewa_lapangan_riwayat');
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

        $totalRecords = DB::table('tb_booking')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
            ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
                $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
            })
            ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
                if($request->filterStatusTrx === 'diproses'){
                    $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Proses');
                }else if($request->filterStatusTrx === 'berhasil'){
                    $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'DP');
                    $query->orWhere('tb_riwayat_status_pembayaran.status_pembayaran', 'Lunas');
                }else if($request->filterStatusTrx === 'tidak berhasil'){
                    $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas');
                    $query->orWhere('tb_riwayat_status_pembayaran.status_pembayaran', 'Batal');

                }
            })
        ->groupBy('tb_pembayaran.id')
        ->get();

        $dataBooking = DB::table('tb_booking')->select('tb_pembayaran.id AS pembayaran_id', 'tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
            ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
                $query->whereBetween('tb_booking.tgl_booking', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
            })
            ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
                if($request->filterStatusTrx === 'diproses'){
                    $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Proses');
                }else if($request->filterStatusTrx === 'berhasil'){
                    // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'DP');
                    $query->whereRaw('tb_riwayat_status_pembayaran.status_pembayaran IN("Lunas", "DP")');
                }else if($request->filterStatusTrx === 'tidak berhasil'){
                    $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Batal');
                    // $query->where('tb_riwayat_status_pembayaran.status_pembayaran', 'Belum Lunas');
                }
            })
            ->groupBy('tb_pembayaran.id')
            ->orderByRaw('tb_pembayaran.id DESC')
            ->skip($start)
            ->take($rowperpage)
            ->get();


        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => count($totalRecords),
            "iTotalDisplayRecords" => count($totalRecords),
            "aaData" => $dataBooking
        );

        return response()->json($response);
    }

    public function getPenyewaLapanganInvoice(Request $request){

        $getPenyewaLapanganInvoice = DB::table('tb_pengguna')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_detail_booking.harga_per_jam',
            'tb_pengguna.name', 'tb_pembayaran.jenis_booking', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_pembayaran.total_biaya', 'tb_pembayaran.id AS pembayaran_id', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
            ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
            ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id', '=', 'tb_pembayaran.id_daftar_jenis_pembayaran')
            ->where('tb_booking.id_pengguna', Auth::user()->id)->where('tb_booking.id_pembayaran', $request->pembayaranId)
            ->get();

        $dataPenyewaLapanganInvoice = array();
        $counter = 0;

        foreach($getPenyewaLapanganInvoice as $getPenyewaLapanganInvoiceIndex => $getPenyewaLapanganInvoiceValue){
            $dataPenyewaLapanganInvoice[$getPenyewaLapanganInvoiceValue->tgl_booking] = [];
        }

        for($countDate= 0; $countDate < count($dataPenyewaLapanganInvoice); $countDate++){
            foreach($getPenyewaLapanganInvoice as $getPenyewaLapanganInvoiceIndex => $getPenyewaLapanganInvoiceValue){
                if(array_keys($dataPenyewaLapanganInvoice)[$countDate] === $getPenyewaLapanganInvoiceValue->tgl_booking){
                    $dataPenyewaLapanganInvoice[$getPenyewaLapanganInvoiceValue->tgl_booking][$counter] = $getPenyewaLapanganInvoiceValue;
                    $counter++;
                }else{
                    $counter = 0;
                }
            }
        }

        return response()->json($dataPenyewaLapanganInvoice);
    }
}
