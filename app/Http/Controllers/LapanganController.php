<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Courts;
use App\Models\StatusCourt;
use App\Models\PaketSewaBulanan;
use App\Models\StatusVerifikasiLapangan;
use App\Models\TipeStatusCourt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LapanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function administratorGetDaftarLapanganBaru(Request $request){
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
        $totalRecords = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
        'tb_status_verifikasi_lapangan.status_verifikasi')
        ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
        ->leftJoin('tb_status_verifikasi_lapangan', function($join){
            $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
            ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
        })
        ->where('tb_status_verifikasi_lapangan.status_verifikasi', 'belum diverifikasi')
        ->skip($start)
        ->take($rowperpage)
        ->get()->count();

        $daftarLapangan = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
        'tb_status_verifikasi_lapangan.status_verifikasi')
        ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
        ->leftJoin('tb_status_verifikasi_lapangan', function($join){
            $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
            ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
        })
        ->where('tb_status_verifikasi_lapangan.status_verifikasi', 'belum diverifikasi')
        // ->groupBy('tb_status_verifikasi_lapangan.id_lapangan')
        ->orderByRaw('tb_lapangan.id DESC')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $daftarLapangan
        );

        return response()->json($response);

    }

    public function administratorDaftarLapangan(){
        return view('admin.admin_list_lapangan');
    }

    public function administratorGetDaftarLapangan(Request $request){
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
        $totalRecords = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
        'tb_status_verifikasi_lapangan.status_verifikasi')
        ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
        ->leftJoin('tb_status_verifikasi_lapangan', function($join){
            $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
            ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
        })
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_lapangan.created_at', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
            if($request->filterStatusTrx === 'ditolak'){
                $query->where('tb_status_verifikasi_lapangan.status_verifikasi', 'ditolak');
            }else if($request->filterStatusTrx === 'disetujui'){
                $query->where('tb_status_verifikasi_lapangan.status_verifikasi', 'disetujui');
            }
        })
        ->skip($start)
        ->take($rowperpage)
        ->get()->count();

        $daftarLapangan = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
        'tb_status_verifikasi_lapangan.status_verifikasi')
        ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
        ->leftJoin('tb_status_verifikasi_lapangan', function($join){
            $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
            ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
        })
        ->when(isset($request->filterTanggalStart), function ($query)  use ($request) {
            $query->whereBetween('tb_lapangan.created_at', [date('Y-m-d', strtotime($request->filterTanggalStart)), date('Y-m-d', strtotime($request->filterTanggalEnd))]);
        })
        ->when(isset($request->filterStatusTrx), function ($query)  use ($request) {
            if($request->filterStatusTrx === 'ditolak'){
                $query->where('tb_status_verifikasi_lapangan.status_verifikasi', 'ditolak');
            }else if($request->filterStatusTrx === 'disetujui'){
                $query->where('tb_status_verifikasi_lapangan.status_verifikasi', 'disetujui');
            }
        })
        ->orderByRaw('tb_lapangan.id DESC')
        ->skip($start)
        ->take($rowperpage)
        ->get();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $daftarLapangan
        );

        return response()->json($response);

    }

    public function administratorViewProfilLapangan($idLapangan){
        $dataLapangan = DB::table('tb_lapangan')->selectRaw(
            'tb_pengguna.name AS nama_pemilik_lapangan, tb_lapangan.id as lapangan_id, tb_lapangan.nama_lapangan, tb_lapangan.alamat_lapangan,
            tb_lapangan.harga_per_jam, tb_lapangan.buka_dari_hari, tb_lapangan.buka_sampai_hari, tb_lapangan.buka_dari_jam, tb_lapangan.buka_sampai_jam, tb_lapangan.jumlah_court,
            tb_lapangan.titik_koordinat_lat, tb_lapangan.titik_koordinat_lng, foto_lapangan_1, foto_lapangan_2, foto_lapangan_3,
            IFNULL(tb_paket_sewa_bulanan.id, "Tidak Tersedia") as status_paket_bulanan'
            )
            ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            ->leftJoin('tb_paket_sewa_bulanan', 'tb_paket_sewa_bulanan.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_lapangan.id', $idLapangan)
            ->first();
            // dd($dataLapangan);
        return view('admin.admin_profil_lapangan_pemilik', compact('dataLapangan'));
    }

    public function administratorApproveProfilLapangan(Request $request){
        $updateStatusVerif = new StatusVerifikasiLapangan;
        $updateStatusVerif->id_pengguna = Auth::user()->id;
        $updateStatusVerif->id_lapangan = $request->id_lapangan;
        $updateStatusVerif->status_verifikasi = 'disetujui';
        $updateStatusVerif->deskripsi_verif_lapangan = $request->alasan_ditolak;
        $updateStatusVerif->save();

        return response()->json('success', 200);
    }

    public function administratorUnapproveProfilLapangan(Request $request){
        $request->validate([
            'alasan_ditolak'  => 'required',
        ]);

        $updateStatusVerif = new StatusVerifikasiLapangan;
        $updateStatusVerif->id_pengguna = Auth::user()->id;
        $updateStatusVerif->id_lapangan = $request->id_lapangan;
        $updateStatusVerif->status_verifikasi = 'ditolak';
        $updateStatusVerif->deskripsi_verif_lapangan = $request->alasan_ditolak;
        $updateStatusVerif->save();

        return response()->json('success', 200);
    }


    public function getDataLapanganPemilik(Request $request){
        $currentDate = date('d-m-Y');

        if($request->tanggal <= $currentDate){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->lapangan_id);

            $dataLapanganBooking = DB::table('tb_pengguna')->select('tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court',
                'tb_riwayat_status_pembayaran.status_pembayaran', 'tb_pembayaran.id AS pembayaran_id', 'tb_pengguna.id as pengguna_id', 'tb_pengguna.name')
                ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->where('tb_lapangan.id', $request->lapangan_id)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                ->get();

            $dataStatusLapangan = DB::table('tb_courts')->select('tb_courts.nomor_court', 'tb_tipe_status_court.tipe_status', 'tb_status_court.detail_status',
            'tb_status_court.id AS status_court_id', 'tb_status_court.jam_status_berlaku_dari', 'tb_status_court.jam_status_berlaku_sampai')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_status_court', 'tb_status_court.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_tipe_status_court', 'tb_tipe_status_court.id', '=', 'tb_status_court.id_tipe_status_court')
                ->where('tb_lapangan.id', $request->lapangan_id)
                ->get();
                // dd($dataStatusLapangan);
            $dataLapanganArr = array();
            $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
            $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);

            for($court= 1; $court <= $dataLapangan->jumlah_court; $court++){
                $row = 0;
                for($dataWaktuLapangan=$lapanganBuka; $dataWaktuLapangan<$lapanganTutup; $dataWaktuLapangan+=3600) {
                    $statusPenyewa = false;
                    $waktuLapangan = date('H:i', $dataWaktuLapangan) . " - ". date('H:i', $dataWaktuLapangan+3600);

                    if(isset($dataLapanganBooking)){
                        foreach($dataLapanganBooking as $dataLapanganBookingKey => $dataLapanganBookingValue){
                            if($court === $dataLapanganBookingValue->nomor_court){
                                for($i=strtotime($dataLapanganBookingValue->jam_mulai); $i < strtotime($dataLapanganBookingValue->jam_selesai); $i+=3600){
                                    if($waktuLapangan === date('H:i', $i) . " - ". date('H:i', $i+3600)){
                                        $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                        $dataLapanganArr['court_'.$court][$row][] = '<td><a data-tooltip="tooltip" data-placement="top" title="" data-original-title="Lihat Data Profil Penyewa" href="javascript:getPenyewa('.$dataLapanganBookingValue->pengguna_id.', '.$dataLapanganBookingValue->nomor_court.', '.$dataLapanganBookingValue->pembayaran_id.')">'.$dataLapanganBookingValue->name.'</a></td>';
                                        if($dataLapanganBookingValue->status_pembayaran === 'Belum Lunas') $dataLapanganArr['court_'.$court][$row][] = 'Penyewa belum melakukan pembayaran';
                                        else if($dataLapanganBookingValue->status_pembayaran === 'Proses') $dataLapanganArr['court_'.$court][$row][] = 'Penyewaan belum diproses';
                                        else if($dataLapanganBookingValue->status_pembayaran === 'DP' || $dataLapanganBookingValue->status_pembayaran === 'Lunas') $dataLapanganArr['court_'.$court][$row][] = 'Penyewaan sudah diproses';
                                        $dataLapanganArr['court_'.$court][$row][] = '<td><button type="button" class="btn btn-square btn-outline-blue" id="edit-data-pengguna" onclick="getPenyewa('.$dataLapanganBookingValue->pengguna_id.', '.$dataLapanganBookingValue->nomor_court.', '.$dataLapanganBookingValue->pembayaran_id.')" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icon-user" style="font-size:20px;"></i></button></td>';
                                        $statusPenyewa = true;
                                    }
                                }
                            }
                        }
                    }
                    foreach($dataStatusLapangan as $dataStatusLapanganKey => $dataStatusLapanganValue){
                        if($court === $dataStatusLapanganValue->nomor_court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                if($dataStatusLapanganValue->tipe_status === 'Tersedia') $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                else if($dataStatusLapanganValue->tipe_status === 'Rusak') $dataLapanganArr['court_'.$court][$row][] = "Rusak";
                                else if($dataStatusLapanganValue->tipe_status === 'Sudah di Booking') $dataLapanganArr['court_'.$court][$row][] = "Sudah di Booking";
                                $dataLapanganArr['court_'.$court][$row][] = '-';
                                $dataLapanganArr['court_'.$court][$row][] = "<td><button type=\"button\" class=\"btn btn-square btn-outline-blue\" id=\"edit-data-court\" onclick=\"editCourt($dataLapangan->lapangan_id, $dataStatusLapanganValue->status_court_id, '$waktuLapangan')\" style=\"width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;\"><i class=\"icon-pencil-alt\" style=\"font-size:20px;\"></i></button></td>";
                            }
                        }
                    }
                    $row++;
                }
            }
            return response()->json($dataLapanganArr);
        }

    }
    //
    public function getStatusCourtLapangan($lapangan_id, $status_court_id){
        // $dataStatusCourtLapangan = Courts::with(['StatusCourt', 'Lapangan'])->where('id_lapangan', $lapangan_id)->where('nomor_court', $court)->get();

        $dataStatusCourtLapangan = DB::table('tb_courts')->select('tb_courts.nomor_court', 'tb_courts.nomor_court', 'tb_tipe_status_court.tipe_status', 'tb_status_court.id AS status_court_id', 'tb_status_court.detail_status',
            'tb_status_court.jam_status_berlaku_dari', 'tb_status_court.jam_status_berlaku_sampai')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_status_court', 'tb_status_court.id_court', '=', 'tb_courts.id')
            ->leftJoin('tb_tipe_status_court', 'tb_tipe_status_court.id', '=', 'tb_status_court.id_tipe_status_court')
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->where('id_lapangan', $lapangan_id)
            ->where('tb_status_court.id', $status_court_id)
            ->get();
        return response()->json($dataStatusCourtLapangan);
    }

    public function updateCourtLapanganStatus(Request $request){
        DB::table('tb_courts')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_status_court', 'tb_status_court.id_court', '=', 'tb_courts.id')
            ->leftJoin('tb_tipe_status_court', 'tb_tipe_status_court.id', '=', 'tb_status_court.id_tipe_status_court')
            // ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            // ->where('id_lapangan', $request->lapangan_id)
            ->where('tb_status_court.id', $request->status_court_id)
            ->update([
                'tb_status_court.id_tipe_status_court' => $request->edit_court_status, 'tb_status_court.detail_status' => $request->edit_court_alasan
            ]);

        // $statusLapangan = StatusCourt::find($request->court_id);
        // $statusLapangan->status = $request->edit_court_status;
        // $statusLapangan->detail_status = $request->edit_court_alasan;
        // $statusLapangan->save();

        return response()->json($request);
    }

    public function getDataLapangan(){
        $dataLapangan = Lapangan::with(['User' => function ($query) {$query->select('tb_pengguna.id', 'tb_pengguna.name', 'tb_pengguna.nomor_telepon'); }])
            ->select(['tb_lapangan.id as lapangan_id', 'tb_lapangan.id_pengguna', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_hari',
            'tb_lapangan.buka_sampai_hari', 'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'tb_lapangan.buka_dari_jam',
            'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court'])
            ->get();

        return response()->json($dataLapangan);
    }

    public function getLapanganPicture($lapangan_id){
        $dataLapangan = Lapangan::select('foto_lapangan_1', 'foto_lapangan_2', 'foto_lapangan_3')->find($lapangan_id);

        return response()->json($dataLapangan);
    }

    public function profilLapangan($idLapangan){

        $dataLapangan = DB::table('tb_courts')->selectRaw(
            'tb_lapangan.id as lapangan_id, tb_lapangan.nama_lapangan, tb_lapangan.alamat_lapangan,
            tb_lapangan.buka_dari_jam, tb_lapangan.buka_sampai_jam, tb_lapangan.jumlah_court,
            tb_lapangan.titik_koordinat_lat, tb_lapangan.titik_koordinat_lng, foto_lapangan_1, foto_lapangan_2, foto_lapangan_3,
            IFNULL(tb_paket_sewa_bulanan.id, "Tidak Tersedia") as status_paket_bulanan, tb_courts.nomor_court'
            )
        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
        ->leftJoin('tb_paket_sewa_bulanan', 'tb_paket_sewa_bulanan.id_lapangan', '=', 'tb_lapangan.id')
        ->where('tb_lapangan.id', $idLapangan)
        ->first();

        $dataLapanganCourt = DB::table('tb_courts')->select('tb_courts.nomor_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->where('tb_lapangan.id', $idLapangan)
            ->where('tb_courts.status_court', '!=', 0)
            ->get();
        // dd($dataLapangan->status_paket_bulanan);
        // $dataLapanganBooking = DB::table('tb_booking')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court')
        //                 ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
        //                 ->where('tb_lapangan.id', $idLapangan)->where('tb_booking.tgl_booking', '2022-05-19')
        //                 ->get();

        // $dataStatusLapangan = DB::table('tb_lapangan')->select('tb_status_lapangan.court', 'tb_status_lapangan.status', 'tb_status_lapangan.detail_status',
        //             'tb_status_lapangan.jam_status_berlaku_dari', 'tb_status_lapangan.jam_status_berlaku_sampai')
        //             ->leftJoin('tb_status_lapangan', 'tb_status_lapangan.id_lapangan', '=', 'tb_lapangan.id')
        //             ->where('tb_lapangan.id', $idLapangan)
        //             ->get();

        // $dataWaktuLapangan = array();
        // $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
        // $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);
        // for($i=$lapanganBuka; $i<$lapanganTutup; $i+=3600) {
        //     $dataWaktuLapangan[] = date('H:i', $i) . " - ". date('H:i', $i+3600);
        // }

        return view('penyewa_lapangan.penyewa_lapangan_profil_lapangan', compact('dataLapangan', 'dataLapanganCourt'));
    }

    public function getDataProfilLapangan(Request $request){
        $currentDate = date('d-m-Y');

        if($currentDate <= $request->tanggal){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->idLapangan);

            $dataLapanganBooking = DB::table('tb_courts')->select('tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->where('tb_courts.id_lapangan', $request->idLapangan)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))
                ->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                ->get();

            $dataStatusLapangan = DB::table('tb_courts')->select('tb_courts.nomor_court', 'tb_tipe_status_court.tipe_status', 'tb_status_court.detail_status',
                'tb_status_court.jam_status_berlaku_dari', 'tb_status_court.jam_status_berlaku_sampai', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->leftJoin('tb_status_court', 'tb_status_court.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_tipe_status_court', 'tb_tipe_status_court.id', '=', 'tb_status_court.id_tipe_status_court')
                ->where('tb_courts.id_lapangan', $request->idLapangan)
                ->get();

                // dd($dataStatusLapangan);
            $dataLapanganArr = array();
            $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
            $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);

            for($court= 1; $court <= $dataLapangan->jumlah_court; $court++){
                $row = 0;
                for($dataWaktuLapangan=$lapanganBuka; $dataWaktuLapangan<$lapanganTutup; $dataWaktuLapangan+=3600) {
                    $statusPenyewa = false;
                    $waktuLapangan = date('H:i', $dataWaktuLapangan) . " - ". date('H:i', $dataWaktuLapangan+3600);

                    if(isset($dataLapanganBooking)){
                        foreach($dataLapanganBooking as $dataLapanganBookingKey => $dataLapanganBookingValue){
                            if($court === $dataLapanganBookingValue->nomor_court){
                                for($i=strtotime($dataLapanganBookingValue->jam_mulai); $i < strtotime($dataLapanganBookingValue->jam_selesai); $i+=3600){
                                    if($waktuLapangan === date('H:i', $i) . " - ". date('H:i', $i+3600)){
                                        $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                        $dataLapanganArr['court_'.$court][$row][] = "Booked";
                                        $statusPenyewa = true;
                                    }
                                }
                            }
                        }
                    }
                    foreach($dataStatusLapangan as $dataStatusLapanganKey => $dataStatusLapanganValue){
                        if($court === $dataStatusLapanganValue->nomor_court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                if($dataStatusLapanganValue->tipe_status === 'Tersedia'){
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                }else if($dataStatusLapanganValue->tipe_status === 'Rusak'){
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Rusak";
                                }else if($dataStatusLapanganValue->tipe_status === 'Sudah di Booking'){
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Sudah di Booking";
                                }
                            }
                        }
                    }
                    $row++;
                }
            }



            return response()->json($dataLapanganArr);
        }
    }

    public function pesanLapanganBulanan($idLapangan){
        $dataLapangan = DB::table('tb_courts')->select('tb_lapangan.id as lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.jumlah_court','tb_lapangan.harga_per_jam',
        'tb_riwayat_status_pembayaran.status_pembayaran')
        ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
        ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
        ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
        })
        ->where('tb_lapangan.id', $idLapangan)
        ->first();

        $dataBookUser = DB::table('tb_booking')->select('tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->first();

        $dataDaftarJenisPembayaranLapangan = DB::table('tb_lapangan')->select('tb_daftar_jenis_pembayaran.id AS daftar_jenis_pembayaran_id', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama',
        'tb_daftar_jenis_pembayaran.no_rekening')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
        ->where('tb_lapangan.id', $idLapangan)
        ->get();

        $dataPaketSewaBulanan = DB::table('tb_lapangan')->select('tb_paket_sewa_bulanan.id AS paket_sewa_bulanan_id', 'tb_paket_sewa_bulanan.total_durasi_jam',
            'tb_paket_sewa_bulanan.total_harga')
            ->leftJoin('tb_paket_sewa_bulanan', 'tb_paket_sewa_bulanan.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_lapangan.id', $idLapangan)
            ->get();

        $dataLapanganCourt = DB::table('tb_courts')->select('tb_courts.nomor_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->where('tb_lapangan.id', $idLapangan)
            ->where('tb_courts.status_court', '!=', 0)
            ->get();

        $jenisBooking = "bulanan";

        return view('penyewa_lapangan.penyewa_lapangan_pesan_lapangan_bulanan', compact('idLapangan', 'dataLapangan', 'dataLapanganCourt', 'dataBookUser', 'dataDaftarJenisPembayaranLapangan', 'jenisBooking', 'dataPaketSewaBulanan'));
    }

    public function pesanLapanganPerJam($idLapangan){
        $dataLapangan = DB::table('tb_courts')->select('tb_lapangan.id as lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.jumlah_court','tb_lapangan.harga_per_jam',
            'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
            ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_lapangan.id', $idLapangan)
            ->first();

        $dataBookUser = DB::table('tb_booking')->select('tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', Auth::user()->id)
            ->first();

        $dataDaftarJenisPembayaranLapangan = DB::table('tb_lapangan')->select('tb_daftar_jenis_pembayaran.id AS daftar_jenis_pembayaran_id', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama',
            'tb_daftar_jenis_pembayaran.no_rekening')
            ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_lapangan.id', $idLapangan)
            ->get();

        $dataLapanganCourt = DB::table('tb_courts')->select('tb_courts.nomor_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->where('tb_lapangan.id', $idLapangan)
            ->where('tb_courts.status_court', '!=', 0)
            ->get();

        //
        // $snapToken = $dataLapangan->snap_token;
        // if (empty($snapToken)) {
        //     $pembayaran = Pembayaran::find($dataMenungguPembayaran->pembayaran_id);
        //     // Jika snap token masih NULL, buat token snap dan simpan ke database

        //     $midtrans = new CreateSnapTokenService($dataMenungguPembayaran->pembayaran_id);
        //     $snapToken = $midtrans->getSnapToken();
        //     // dd($snapToken);
        //     $pembayaran->snap_token = $snapToken;
        //     $pembayaran->save();
        // }

        return view('penyewa_lapangan.penyewa_lapangan_pesan_lapangan_per_jam', compact('idLapangan', 'dataLapangan', 'dataLapanganCourt', 'dataBookUser', 'dataDaftarJenisPembayaranLapangan'));
    }

    public function getAllDataLapangan(Request $request){
        $currentDate = date('d-m-Y');

        if(date('Y-m-d', strtotime($currentDate)) <= date('Y-m-d', strtotime($request->tanggal))){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->idLapangan);

            $dataLapanganBooking = DB::table('tb_courts')->select('tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_detail_booking', 'tb_detail_booking.id_booking', '=', 'tb_booking.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->where('tb_courts.id_lapangan', $request->idLapangan)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))
                ->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                ->get();

            $dataStatusLapangan = DB::table('tb_courts')->select('tb_courts.nomor_court', 'tb_tipe_status_court.tipe_status', 'tb_status_court.detail_status',
            'tb_status_court.jam_status_berlaku_dari', 'tb_status_court.jam_status_berlaku_sampai', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
                ->leftJoin('tb_booking', 'tb_booking.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                })
                ->leftJoin('tb_status_court', 'tb_status_court.id_court', '=', 'tb_courts.id')
                ->leftJoin('tb_tipe_status_court', 'tb_tipe_status_court.id', '=', 'tb_status_court.id_tipe_status_court')
                ->where('tb_courts.id_lapangan', $request->idLapangan)
                ->get();

            $dataBookUser = DB::table('tb_booking')->select('tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->where('tb_booking.id_pengguna', Auth::user()->id)
                ->first();

            $dataLapanganArr = array();
            $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
            $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);

            for($court= 1; $court <= $dataLapangan->jumlah_court; $court++){
                $row = 0;
                for($dataWaktuLapangan= $lapanganBuka; $dataWaktuLapangan < $lapanganTutup; $dataWaktuLapangan +=3600){
                    $statusPenyewa = false;
                    $waktuLapangan = date('H:i', $dataWaktuLapangan) . " - ". date('H:i', $dataWaktuLapangan +3600);

                    if(isset($dataLapanganBooking)){
                        foreach($dataLapanganBooking as $dataLapanganBookingKey => $dataLapanganBookingValue){
                            if($court === $dataLapanganBookingValue->nomor_court){
                                for($i=strtotime($dataLapanganBookingValue->jam_mulai); $i < strtotime($dataLapanganBookingValue->jam_selesai); $i+=3600){
                                    if($waktuLapangan === date('H:i', $i) . " - ". date('H:i', $i+3600)){
                                        $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                        $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                        $dataLapanganArr['court_'.$court][$row][] = "Booked";
                                        $statusPenyewa = true;
                                    }
                                }
                            }
                        }
                    }

                    foreach($dataStatusLapangan as $dataStatusLapanganKey => $dataStatusLapanganValue){
                        if($court === $dataStatusLapanganValue->nomor_court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                if($dataStatusLapanganValue->tipe_status === 'Tersedia'){
                                    if($dataStatusLapanganValue->status_pembayaran === 'Belum Lunas' || isset($dataBookUser->status_pembayaran) && $dataBookUser->status_pembayaran === 'Belum Lunas'){
                                        $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                    }else{
                                        $dataLapanganArr['court_'.$court][$row][] = "<input name=\"checkBook[]\" value='{\"lapangan_id\":$dataLapangan->lapangan_id,\"court\":$dataStatusLapanganValue->nomor_court,\"jam\":\"$waktuLapangan\"}' type=\"checkbox\">";
                                    }
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                }else if($dataStatusLapanganValue->tipe_status === 'Rusak'){
                                    $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Rusak";
                                }else if($dataStatusLapanganValue->tipe_status === 'Sudah di Booking'){
                                    $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Sudah di Booking";
                                }
                            }
                        }
                    }
                    $row++;
                }
            }
            return response()->json($dataLapanganArr);
        }
    }

    public function manajemenPaketBulananPemilik(){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataPaketSewaBulanan = DB::table('tb_lapangan')->select('tb_paket_sewa_bulanan.id AS paket_sewa_bulanan_id', 'tb_paket_sewa_bulanan.total_durasi_jam',
            'tb_paket_sewa_bulanan.total_harga')
            ->leftJoin('tb_paket_sewa_bulanan', 'tb_paket_sewa_bulanan.id_lapangan', '=', 'tb_lapangan.id')
            ->where('tb_lapangan.id', $lapanganId->id)
            ->get();

        return view('pemilik_lapangan.pemilik_lapangan_manajemen_paket_bulanan', compact('dataPaketSewaBulanan'));
    }

    public function updateOrCreatePaketBulananPemilik(Request $request){
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        PaketSewaBulanan::updateOrCreate([
            'tb_paket_sewa_bulanan.id' => $request->paket_sewa_bulanan_id
        ],[
            'id_lapangan' => $lapanganId->id,
            'total_durasi_jam' => $request->total_durasi_waktu_jam,
            'total_harga' => $request->total_harga
        ]);

        return response()->json('success');
    }

    public function pemilikLapanganCourts(){
        return view('pemilik_lapangan.pemilik_lapangan_edit_courts');
    }

    public function pemilikLapanganGetDataCourts(Request $request){
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $dataLapangan = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $totalRecords = Courts::where('id_lapangan', $dataLapangan->id)->count();

        $dataCourts = Courts::select('tb_courts.id AS court_id', 'tb_courts.nomor_court', 'tb_courts.status_court')->where('id_lapangan', $dataLapangan->id)->get();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $dataCourts
        );
        return response()->json($response);
    }

    public function pemilikLapanganAddCourt(){
        $dataLapangan = Lapangan::select('tb_lapangan.id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $totalCourts = Courts::where('id_lapangan', $dataLapangan->id)->count();

        $dataCourt = new Courts();
        $dataCourt->id_lapangan = $dataLapangan->id;
        $dataCourt->nomor_court = $totalCourts+1;
        $dataCourt->status_court = 1;
        $dataCourt->save();

        $dataLapangan->jumlah_court = $totalCourts+1;
        $dataLapangan->save();

        $dataCourts = Courts::where('tb_courts.id_lapangan', $dataLapangan->id)->get();

        $dataTipeStatusCourt = TipeStatusCourt::where('tb_tipe_status_court.tipe_status', 'Tersedia')->first();

        $statusCourtArr = array();
        $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
        $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);

        foreach($dataCourts as $dataCourt){
            for($jam=$lapanganBuka; $jam<$lapanganTutup; $jam+=3600){
                array_push($statusCourtArr, array(
                    'id_court' => $dataCourt->id,
                    'id_tipe_status_court' => $dataTipeStatusCourt->id,
                    'jam_status_berlaku_dari' => date('H:i', $jam),
                    'jam_status_berlaku_sampai' => date('H:i', $jam + 3600)
                ));
            }
        }

        StatusCourt::insert($statusCourtArr);

        return response()->json('success', 200);
    }

    public function pemilikLapanganRestoreCourt(Request $request){
        $dataLapangan = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataCourt = Courts::where(['tb_courts.id' => $request->court_id, 'tb_courts.id_lapangan' => $dataLapangan->id])->first();
        $dataCourt->status_court = 1;
        $dataCourt->save();

        $countCourt = Courts::where('tb_courts.id_lapangan', $dataLapangan->id)->where('tb_courts.status_court', '=', 1)->get()->count();

        $dataLapangan->jumlah_court = $countCourt;
        $dataLapangan->save();

        return response()->json('success', 200);
    }

    public function pemilikLapanganDeleteCourt(Request $request){
        $dataLapangan = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataCourt = Courts::where(['tb_courts.id' => $request->court_id, 'tb_courts.id_lapangan' => $dataLapangan->id])->first();
        $dataCourt->status_court = 0;
        $dataCourt->save();

        $countCourt = Courts::where('tb_courts.id_lapangan', $dataLapangan->id)->where('tb_courts.status_court', '!=', 0)->get()->count();

        $dataLapangan->jumlah_court = $countCourt;
        $dataLapangan->save();

        return response()->json('success', 200);
    }

    public function pemilikLapanganEditWaktuOperasionalLapangan(){
        $dataWaktuOperasional = Lapangan::select('tb_lapangan.buka_dari_hari', 'tb_lapangan.buka_sampai_hari', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam')
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();
        return view('pemilik_lapangan.pemilik_lapangan_edit_waktu_operasional_lapangan', compact('dataWaktuOperasional'));
    }

    public function pemilikLapanganUpdateWaktuOperasionalLapangan(Request $request){
        $dataLapangan = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataCourts = Courts::where('tb_courts.id_lapangan', $dataLapangan->id)->get();

        $dataTipeStatusCourt = TipeStatusCourt::where('tb_tipe_status_court.tipe_status', 'Tersedia')->first();

        $dataLapanganUpdate = Lapangan::find($dataLapangan->id);
        $dataLapanganUpdate->buka_dari_hari = $request->lapangan_buka_dari_hari;
        $dataLapanganUpdate->buka_sampai_hari = $request->lapangan_buka_sampai_hari;
        $dataLapanganUpdate->buka_dari_jam = $request->lapangan_buka_dari_jam;
        $dataLapanganUpdate->buka_sampai_jam = $request->lapangan_buka_sampai_jam;
        $dataLapanganUpdate->save();

        $dataCourtArr = array();
        $statusCourtArr = array();
        $lapanganBuka = strtotime($request->lapangan_buka_dari_jam);
        $lapanganTutup = strtotime($request->lapangan_buka_sampai_jam);

        foreach($dataCourts as $dataCourt){
            for($jam=$lapanganBuka; $jam<$lapanganTutup; $jam+=3600){
                array_push($statusCourtArr, array(
                    'id_court' => $dataCourt->id,
                    'id_tipe_status_court' => $dataTipeStatusCourt->id,
                    'jam_status_berlaku_dari' => date('H:i', $jam),
                    'jam_status_berlaku_sampai' => date('H:i', $jam + 3600)
                ));
            }
        }
        // for($court= 1; $court <= $dataLapangan->jumlah_court; $court++){
        //     array_push($dataCourtArr, array(
        //         'id_lapangan' => $dataLapangan->id,
        //         'nomor_court' => $court
        //     ));

        //     for($jam=$lapanganBuka; $jam<$lapanganTutup; $jam+=3600){
        //         array_push($statusLapanganArr, array(
        //             'court' => $court,
        //             'status' => 'Available',
        //             'jam_status_berlaku_dari' => date('H:i', $jam),
        //             'jam_status_berlaku_sampai' => date('H:i', $jam + 3600)
        //         ));
        //     }
        // }

        // dd($statusCourtArr);

        StatusCourt::insert($statusCourtArr);
    }
}
