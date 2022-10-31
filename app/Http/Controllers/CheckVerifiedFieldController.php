<?php

namespace App\Http\Controllers;

use App\Models\Courts;
use App\Models\Lapangan;
use App\Models\StatusCourt;
use Illuminate\Http\Request;
use App\Models\TipeStatusCourt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Models\StatusVerifikasiLapangan;

class CheckVerifiedFieldController extends Controller
{
    public function belumDiverif(){
        $dataLapanganVerif = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
            'tb_status_verifikasi_lapangan.status_verifikasi')
            ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            ->leftJoin('tb_status_verifikasi_lapangan', function($join){
                $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
            })
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        if($dataLapanganVerif->status_verifikasi === 'belum diverifikasi'){
            return view('pemilik_lapangan.penyewa_lapangan_menunggu_verifikasi_lapangan');
        }else if($dataLapanganVerif->status_verifikasi === 'ditolak'){
            return redirect(route('pemilik_lapangan.verifikasiDitolak'));
        }

        return redirect(route('pemilikLapangan.dashboard'));
    }

    public function verifikasiDitolak(){
        $dataLapanganVerif = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
            'tb_status_verifikasi_lapangan.status_verifikasi', 'tb_status_verifikasi_lapangan.deskripsi_verif_lapangan')
            ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            ->leftJoin('tb_status_verifikasi_lapangan', function($join){
                $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
            })
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        if($dataLapanganVerif->status_verifikasi === 'ditolak'){
            return view('pemilik_lapangan.pemilik_lapangan_verif_ditolak', compact('dataLapanganVerif'));
        }

        return redirect(route('pemilikLapangan.dashboard'));
    }

    public function editDataVerifLapangan(){
        $dataLapangan = DB::table('tb_lapangan')->select('tb_pengguna.name AS nama_pemilik_lapangan', 'tb_pengguna.email', 'tb_pengguna.nomor_telepon',
            'tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_lapangan.buka_dari_hari', 'tb_lapangan.buka_sampai_hari',
            'tb_lapangan.titik_koordinat_lat', 'tb_lapangan.titik_koordinat_lng', 'tb_lapangan.buka_dari_jam', 'tb_lapangan.buka_sampai_jam', 'tb_lapangan.jumlah_court',
            'tb_lapangan.harga_per_jam', 'tb_lapangan.foto_lapangan_1', 'tb_lapangan.foto_lapangan_2', 'tb_lapangan.foto_lapangan_3',
            'tb_status_verifikasi_lapangan.status_verifikasi')
            ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            ->leftJoin('tb_status_verifikasi_lapangan', function($join){
                $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
            })
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

            $lapanganImage = array();
            if($dataLapangan->foto_lapangan_1 !== ""){
                $lapanganImage['foto_lapangan_1'] = $dataLapangan->foto_lapangan_1;

            }else{
                $lapanganImage['foto_lapangan_1'] = null;
            }

            if($dataLapangan->foto_lapangan_2 !== ""){
                $lapanganImage['foto_lapangan_2'] = $dataLapangan->foto_lapangan_2;

            }else{
                $lapanganImage['foto_lapangan_2'] = null;
            }

            if($dataLapangan->foto_lapangan_3 !== ""){
                $fileExtension = pathinfo(storage_path($dataLapangan->foto_lapangan_3), PATHINFO_EXTENSION);
                $lapanganImage['foto_lapangan_3'] = $dataLapangan->foto_lapangan_3;

            }else{
                $lapanganImage['foto_lapangan_3'] = null;
            }

        if($dataLapangan->status_verifikasi === 'ditolak'){
            return view('pemilik_lapangan.pemilik_lapangan_edit_data_verif_lapangan', compact('dataLapangan', 'lapanganImage'));
        }

        return redirect(route('pemilikLapangan.dashboard'));
    }

    public function updateDataVerifLapangan(Request $request){
        $dataLapangan = DB::table('tb_lapangan')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_pengguna.name AS nama_pemilik_lapangan',
            'tb_status_verifikasi_lapangan.status_verifikasi')
            ->leftJoin('tb_pengguna', 'tb_pengguna.id', '=', 'tb_lapangan.id_pengguna')
            ->leftJoin('tb_status_verifikasi_lapangan', function($join){
                $join->on('tb_status_verifikasi_lapangan.id_lapangan', '=', 'tb_lapangan.id')
                ->whereRaw('tb_status_verifikasi_lapangan.id IN (SELECT MAX(tb_status_verifikasi_lapangan.id) FROM tb_status_verifikasi_lapangan GROUP BY tb_status_verifikasi_lapangan.id_lapangan)');
            })
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        if($dataLapangan->status_verifikasi === 'ditolak'){
            $dataLapanganUpdate = Lapangan::find($dataLapangan->lapangan_id);
            $dataLapanganUpdate->nama_lapangan = $request->nama_lapangan_pemilik_lapangan;
            $dataLapanganUpdate->alamat_lapangan = $request->alamat_tertulis_pemilik_lapangan;
            $dataLapanganUpdate->buka_dari_hari = $request->lapangan_buka_dari_hari;
            $dataLapanganUpdate->buka_sampai_hari = $request->lapangan_buka_sampai_hari;
            $dataLapanganUpdate->buka_dari_jam = $request->lapangan_buka_dari_jam;
            $dataLapanganUpdate->buka_sampai_jam = $request->lapangan_buka_sampai_jam;
            $dataLapanganUpdate->titik_koordinat_lat = $request->lat_alamat_pemilik_lapangan;
            $dataLapanganUpdate->titik_koordinat_lng = $request->lng_alamat_pemilik_lapangan;
            $dataLapanganUpdate->harga_per_jam = $request->harga_lapangan_per_jam;
            $dataLapanganUpdate->jumlah_court = $request->jumlah_court_pemilik_lapangan;

            if ($request->hasFile('foto_lapangan_1')) {
                File::delete($dataLapangan->foto_lapangan_1);

                $userPath = 'file/'.Auth::user()->id.'/';
                Storage::disk('public')->makeDirectory($userPath);
                $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
                Storage::disk('public')->makeDirectory($fotoLapanganPath);
                $pathFotoLapangan_1 = $request->file('foto_lapangan_1')->storeAs(
                    $fotoLapanganPath, "foto_lapangan_1.jpg", 'public'
                );
                $dataLapanganUpdate->foto_lapangan_1 = $pathFotoLapangan_1;
            }
            if ($request->hasFile('foto_lapangan_2')) {
                File::delete($dataLapangan->foto_lapangan_2);

                $userPath = 'file/'.Auth::user()->id.'/';
                Storage::disk('public')->makeDirectory($userPath);
                $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
                Storage::disk('public')->makeDirectory($fotoLapanganPath);
                $pathFotoLapangan_2 = $request->file('foto_lapangan_2')->storeAs(
                    $fotoLapanganPath, "foto_lapangan_2.jpg", 'public'
                );
                $dataLapanganUpdate->foto_lapangan_2 = $pathFotoLapangan_2;
            }
            if ($request->hasFile('foto_lapangan_3')) {
                File::delete($dataLapangan->foto_lapangan_3);

                $userPath = 'file/'.Auth::user()->id.'/';
                Storage::disk('public')->makeDirectory($userPath);
                $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
                Storage::disk('public')->makeDirectory($fotoLapanganPath);
                $pathFotoLapangan_3 = $request->file('foto_lapangan_3')->storeAs(
                    $fotoLapanganPath, "foto_lapangan_3.jpg", 'public'
                );
                $dataLapanganUpdate->foto_lapangan_3 = $pathFotoLapangan_3;
            }

            $dataLapanganUpdate->save();

            $dataCourts = Courts::select('tb_courts.id')->where('tb_courts.id_lapangan', $dataLapangan->lapangan_id)->get();

            foreach($dataCourts as $dataCourt){
                StatusCourt::where('id_court', $dataCourt->id)->delete();
            }

            Courts::where('id_lapangan', $dataLapangan->lapangan_id)->delete();

            $dataCourtArr = array();

            for($courtCount= 0; $courtCount<$request->jumlah_court_pemilik_lapangan; $courtCount++){
                array_push($dataCourtArr, array(
                    'id_lapangan' => $dataLapangan->lapangan_id,
                    'nomor_court' => $courtCount+1,
                    'status_court' => 1
                ));
            }
            Courts::insert($dataCourtArr);

            $dataCourts = Courts::where('tb_courts.id_lapangan', $dataLapangan->lapangan_id)->get();

            $dataTipeStatusCourt = TipeStatusCourt::where('tb_tipe_status_court.tipe_status', 'Tersedia')->first();


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

            StatusCourt::insert($statusCourtArr);

            $statusLapanganVerif = new StatusVerifikasiLapangan();
            $statusLapanganVerif->id_lapangan = $dataLapangan->lapangan_id;
            $statusLapanganVerif->status_verifikasi = 'belum diverifikasi';
            $statusLapanganVerif->save();

            return response()->json('success');
        }

        return response("Forbidden access.", 403);
    }
}
