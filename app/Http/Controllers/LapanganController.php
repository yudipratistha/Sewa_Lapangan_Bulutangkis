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

    public function getAllDataLapangan(){
        $dataLapangan = Lapangan::with(['User' => function ($query) {$query->select('tb_pengguna.id', 'tb_pengguna.name', 'tb_pengguna.nomor_telepon'); }])
            ->select(['tb_lapangan.id as lapangan_id', 'tb_lapangan.id_pengguna', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_hari', 
            'tb_lapangan.buka_sampai_hari', 'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'tb_lapangan.buka_dari_jam', 
            'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court'])
            ->get();

        return response()->json($dataLapangan);
    }

    public function getLapanganPicture($lapangan_id){
        $dataLapangan = Lapangan::select('foto_lapangan_1' ,'foto_lapangan_2' ,'foto_lapangan_3')->find($lapangan_id);
        
        // $lapanganPictureArr = array();
                
        // if(isset($dataLapangan['foto_lapangan_1']) && $dataLapangan['foto_lapangan_1'] !== ""){
        //     $fileExtension = pathinfo(storage_path($dataLapangan['foto_lapangan_1']), PATHINFO_EXTENSION);
        //     $lapanganPictureArr[] = 'data:image/'.$fileExtension.';base64,'.base64_encode(file_get_contents(storage_path($dataLapangan['foto_lapangan_1'])));
        // }else{
        //     $lapanganPictureArr[] = null;
        // }

        // if(isset($dataLapangan['foto_lapangan_2']) && $dataLapangan['foto_lapangan_2'] !== ""){
        //     $fileExtension = pathinfo(storage_path($dataLapangan['foto_lapangan_2']), PATHINFO_EXTENSION);
        //     $lapanganPictureArr[] = 'data:image/'.$fileExtension.';base64,'.base64_encode(file_get_contents(storage_path($dataLapangan['foto_lapangan_2'])));
        // }else{
        //     $lapanganPictureArr[] = null;
        // }

        // if(isset($dataLapangan['foto_lapangan_3']) && $dataLapangan['foto_lapangan_3'] !== ""){
        //     $file = File::get($path);

        //     $type = File::mimeType($path);
        //     $fileExtension = pathinfo(storage_path($dataLapangan['foto_lapangan_3']), PATHINFO_EXTENSION);
        //     $lapanganPictureArr[] = 'data:image/'.$fileExtension.';base64,'.base64_encode(file_get_contents(storage_path($dataLapangan['foto_lapangan_3'])));
        // }else{
        //     $lapanganPictureArr[] = null;
        // }
        
        // $lapanganImage = 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_1'])));
        return response()->json($dataLapangan);
    }

    public function getLapangan($idLapangan){
        $lapangan = Lapangan::find($idLapangan);

        return view('penyewa_lapangan.penyewa_lapangan_profil_lapangan');
    }
}
