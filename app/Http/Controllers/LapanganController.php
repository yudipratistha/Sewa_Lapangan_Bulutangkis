<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\StatusLapangan;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LapanganController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getDataLapanganPemilik(Request $request){
        $currentDate = date('d-m-Y');
        
        if($request->tanggal <= $currentDate){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->lapangan_id);

            $dataLapanganBooking = DB::table('tb_pengguna')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 
                        'tb_riwayat_status_pembayaran.status_pembayaran', 'tb_pembayaran.id AS pembayaran_id', 'tb_pengguna.id as pengguna_id', 'tb_pengguna.name')
                        ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
                        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
                        })
                        ->where('tb_lapangan.id', $request->lapangan_id)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                        ->get();

            $dataStatusLapangan = DB::table('tb_lapangan')->select('tb_status_lapangan.court', 'tb_status_lapangan.status', 'tb_status_lapangan.detail_status',
                        'tb_status_lapangan.jam_status_berlaku_dari', 'tb_status_lapangan.jam_status_berlaku_sampai')
                        ->leftJoin('tb_status_lapangan', 'tb_status_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                        ->where('tb_lapangan.id', $request->lapangan_id)
                        ->get();

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
                            if($court === $dataLapanganBookingValue->court){
                                for($i=strtotime($dataLapanganBookingValue->jam_mulai); $i < strtotime($dataLapanganBookingValue->jam_selesai); $i+=3600){
                                    if($waktuLapangan === date('H:i', $i) . " - ". date('H:i', $i+3600)){
                                        $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                        $dataLapanganArr['court_'.$court][$row][] = '<td><a data-tooltip="tooltip" data-placement="top" title="" data-original-title="Lihat Data Profil Penyewa" href="javascript:getPenyewa('.$dataLapanganBookingValue->pengguna_id.', '.$dataLapanganBookingValue->court.', '.$dataLapanganBookingValue->pembayaran_id.')">'.$dataLapanganBookingValue->name.'</a></td>';
                                        if($dataLapanganBookingValue->status_pembayaran === 'Belum Lunas') $dataLapanganArr['court_'.$court][$row][] = 'Penyewa belum melakukan pembayaran';
                                        else if($dataLapanganBookingValue->status_pembayaran === 'Proses') $dataLapanganArr['court_'.$court][$row][] = 'Penyewaan belum diproses';
                                        else if($dataLapanganBookingValue->status_pembayaran === 'DP' || $dataLapanganBookingValue->status_pembayaran === 'Lunas') $dataLapanganArr['court_'.$court][$row][] = 'Penyewaan sudah diproses';
                                        $dataLapanganArr['court_'.$court][$row][] = '<td><button type="button" class="btn btn-square btn-outline-blue" id="edit-data-pengguna" onclick="getPenyewa('.$dataLapanganBookingValue->pengguna_id.', '.$dataLapanganBookingValue->court.', '.$dataLapanganBookingValue->pembayaran_id.')" style="width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;"><i class="icon-user" style="font-size:20px;"></i></button></td>';
                                        $statusPenyewa = true;
                                    }
                                }
                            }
                        }
                    }
                    foreach($dataStatusLapangan as $dataStatusLapanganKey => $dataStatusLapanganValue){
                        if($court === $dataStatusLapanganValue->court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                if($dataStatusLapanganValue->status === 'Available') $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                else if($dataStatusLapanganValue->status === 'Unavailable') $dataLapanganArr['court_'.$court][$row][] = "Tidak Tersedia";
                                $dataLapanganArr['court_'.$court][$row][] = '-';
                                $dataLapanganArr['court_'.$court][$row][] = "<td><button type=\"button\" class=\"btn btn-square btn-outline-blue\" id=\"edit-data-court\" onclick=\"editCourt($dataLapangan->lapangan_id, $court, '$waktuLapangan')\" style=\"width: 37px; padding-top: 2px; padding-left: 0px; padding-right: 0px; padding-bottom: 2px; margin-right:5px;\"><i class=\"icon-pencil-alt\" style=\"font-size:20px;\"></i></button></td>";
                            }
                        }
                    }
                    $row++;
                }
            }
            return response()->json($dataLapanganArr); 
        }
        
    }

    public function getStatusCourtLapangan($lapangan_id, $court){
        $dataStatusCourtLapangan = StatusLapangan::with('Lapangan')->where('id_lapangan', $lapangan_id)->where('court', $court)->get();
        return response()->json($dataStatusCourtLapangan);
    }

    public function updateCourtLapanganStatus(Request $request){
        $statusLapangan = StatusLapangan::find($request->court_id);
        $statusLapangan->status = $request->edit_court_status;
        $statusLapangan->detail_status = $request->edit_court_alasan;
        $statusLapangan->save();

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

        $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court', 'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'foto_lapangan_1', 'foto_lapangan_2', 'foto_lapangan_3')
                ->find($idLapangan);

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

        return view('penyewa_lapangan.penyewa_lapangan_profil_lapangan', compact('dataLapangan'));
    }

    public function getDataProfilLapangan(Request $request){
        $currentDate = date('d-m-Y');
        
        if($request->tanggal <= $currentDate){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->idLapangan);

            $dataLapanganBooking = DB::table('tb_booking')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 'tb_riwayat_status_pembayaran.status_pembayaran')
                        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id');
                        })
                        ->where('tb_lapangan.id', $request->idLapangan)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                        ->get();

            $dataStatusLapangan = DB::table('tb_booking')->select('tb_status_lapangan.court', 'tb_status_lapangan.status', 'tb_status_lapangan.detail_status',
                        'tb_status_lapangan.jam_status_berlaku_dari', 'tb_status_lapangan.jam_status_berlaku_sampai', 'tb_riwayat_status_pembayaran.status_pembayaran')
                        ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                        })
                        ->leftJoin('tb_status_lapangan', 'tb_status_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                        ->where('tb_lapangan.id', $request->idLapangan)
                        ->get();

            // dd($dataLapanganBooking);
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
                            if($court === $dataLapanganBookingValue->court){
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
                        if($court === $dataStatusLapanganValue->court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                if($dataStatusLapanganValue->status === 'Available'){
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                }else if($dataStatusLapanganValue->status === 'Unavailable'){
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tidak Tersedia";
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

    public function pesanLapangan($idLapangan){
        $dataLapangan = DB::table('tb_lapangan')->select('tb_lapangan.id as lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.jumlah_court','tb_lapangan.harga_per_jam',
        'tb_riwayat_status_pembayaran.status_pembayaran')
        ->leftJoin('tb_booking', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
        ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
        ->leftJoin('tb_riwayat_status_pembayaran', function($join){
            $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
            ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
        })
        
        ->where('tb_lapangan.id', $idLapangan)
        ->first();
        
        $dataDaftarJenisPembayaranLapangan = DB::table('tb_lapangan')->select('tb_daftar_jenis_pembayaran.id AS daftar_jenis_pembayaran_id', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.atas_nama', 
        'tb_daftar_jenis_pembayaran.no_rekening')
        ->leftJoin('tb_daftar_jenis_pembayaran', 'tb_daftar_jenis_pembayaran.id_lapangan', '=', 'tb_lapangan.id')
        ->where('tb_lapangan.id', $idLapangan)
        ->get();
        // dd($dataLapangan);
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
        
        return view('penyewa_lapangan.penyewa_lapangan_pesan_lapangan', compact('idLapangan', 'dataLapangan', 'dataDaftarJenisPembayaranLapangan'));
    }

    public function getAllDataLapangan(Request $request){
        $currentDate = date('d-m-Y');
        
        if($request->tanggal <= $currentDate){
            $dataLapangan = Lapangan::select('tb_lapangan.id as lapangan_id', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court')
                ->find($request->idLapangan);

            $dataLapanganBooking = DB::table('tb_booking')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id');
                })
                ->where('tb_lapangan.id', $request->idLapangan)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($request->tanggal)))->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
                ->get();

            $dataStatusLapangan = DB::table('tb_booking')->select('tb_status_lapangan.court', 'tb_status_lapangan.status', 'tb_status_lapangan.detail_status',
                'tb_status_lapangan.jam_status_berlaku_dari', 'tb_status_lapangan.jam_status_berlaku_sampai', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->leftJoin('tb_status_lapangan', 'tb_status_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                ->where('tb_lapangan.id', $request->idLapangan)
                ->get();

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
                            if($court === $dataLapanganBookingValue->court){
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
                        if($court === $dataStatusLapanganValue->court){
                            if($statusPenyewa !== true && $waktuLapangan === date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_dari)) . " - ". date('H:i', strtotime($dataStatusLapanganValue->jam_status_berlaku_sampai))){
                                if($dataStatusLapanganValue->status === 'Available'){
                                    if($dataStatusLapanganValue->status_pembayaran === 'Belum Lunas'){
                                        $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                    }else{
                                        $dataLapanganArr['court_'.$court][$row][] = "<input name=\"checkBook[]\" value='{\"lapangan_id\":$dataLapangan->lapangan_id,\"court\":$dataStatusLapanganValue->court,\"jam\":\"$waktuLapangan\"}' type=\"checkbox\">";
                                    }
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tersedia";
                                }else if($dataStatusLapanganValue->status === 'Unavailable'){
                                    $dataLapanganArr['court_'.$court][$row][] = '<input name="checkBook[]" value="" type="checkbox" style="cursor: not-allowed;" disabled>';
                                    $dataLapanganArr['court_'.$court][$row][] = $waktuLapangan;
                                    $dataLapanganArr['court_'.$court][$row][] = "Tidak Tersedia";
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
}
