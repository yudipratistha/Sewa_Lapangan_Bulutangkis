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
        $dataLapangan = Lapangan::select('*')
            ->with(['User' => function ($query) {$query->select('id', 'name', 'nomor_telepon'); }])
            ->get();
        
        $dataLapanganArr = array();
        foreach($dataLapangan as $dataLapanganKey => $dataLapanganValue){
            array_push($dataLapanganArr, array(
                'nama_lapangan' => $dataLapanganValue->nama_lapangan,
                'nama_pemilik_lapangan' => $dataLapanganValue->user->name,
                'nomor_telepon_lapangan' => $dataLapanganValue->user->nomor_telepon,
                'alamat_lapangan' => $dataLapanganValue->alamat_lapangan,
                'buka_dari_hari' => $dataLapanganValue->buka_dari_hari,
                'buka_sampai_hari' => $dataLapanganValue->buka_sampai_hari,
                'titik_koordinat_lat' => $dataLapanganValue->titik_koordinat_lat, 
                'titik_koordinat_lng' => $dataLapanganValue->titik_koordinat_lng,
                'buka_dari_jam' => $dataLapanganValue->buka_dari_jam, 
                'buka_sampai_jam' => $dataLapanganValue->buka_sampai_jam, 
                'jumlah_court' => $dataLapanganValue->jumlah_court, 
                'foto_lapangan_1' => 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataLapanganValue->foto_lapangan_1))), 
                'foto_lapangan_2' => 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataLapanganValue->foto_lapangan_2))), 
                'foto_lapangan_3' => 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataLapanganValue->foto_lapangan_3)))
            ));
        }

        // $lapanganImage = 'data:image/jpg;base64,'.base64_encode(file_get_contents(storage_path($dataProfilPemilikLapangan->toArray()[0]['foto_lapangan_1'])));
        return response()->json($dataLapanganArr);
    }
}
