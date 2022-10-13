<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }


    public function pemilikLapanganProfil(){
        $dataProfilPemilikLapangan = Lapangan::with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();
        // echo '<pre>'; print_r($dataProfilPemilikLapangan->toArray());echo'<pre>';
        // die();
        $lapanganImage = array();
        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_1'] !== ""){
            $lapanganImage['foto_lapangan_1'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_1'];

        }else{
            $lapanganImage['foto_lapangan_1'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_2'] !== ""){
            $lapanganImage['foto_lapangan_2'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_2'];

        }else{
            $lapanganImage['foto_lapangan_2'] = null;
        }

        if($dataProfilPemilikLapangan->toArray()['foto_lapangan_3'] !== ""){
            $fileExtension = pathinfo(storage_path($dataProfilPemilikLapangan->toArray()['foto_lapangan_3']), PATHINFO_EXTENSION);
            $lapanganImage['foto_lapangan_3'] = $dataProfilPemilikLapangan->toArray()['foto_lapangan_3'];

        }else{
            $lapanganImage['foto_lapangan_3'] = null;
        }
        // $response->header("Content-Type", 'image/jpg');
        // $image = $response;
        // dd($fileExtension);
        // die();
        return view('pemilik_lapangan.pemilik_lapangan_profil', compact('dataProfilPemilikLapangan', 'lapanganImage'));
    }

    public function pemilikLapanganUpdateProfil(Request $request){
        $user = new User;

        $user->name = $request->nama_pemilik_lapangan;
        $user->email = $request->email_pemilik_lapangan;
        $user->nomor_telepon = $request->nomor_telepon_pemilik_lapangan;
        $user->user_status = 2;
        $user->save();

        $lapangan = new Lapangan;
        $lapangan->nama_lapangan = $request->nama_lapangan_pemilik_lapangan;
        $lapangan->alamat_lapangan = $request->alamat_tertulis_pemilik_lapangan;
        $lapangan->id_pengguna = $user->id;
        $lapangan->buka_dari_hari = $request->lapangan_buka_dari_hari;
        $lapangan->buka_sampai_hari = $request->lapangan_buka_sampai_hari;
        $lapangan->buka_dari_jam = $request->lapangan_buka_dari_jam;
        $lapangan->buka_sampai_jam = $request->lapangan_buka_sampai_jam;
        $lapangan->titik_koordinat_lat = $request->lat_alamat_pemilik_lapangan;
        $lapangan->titik_koordinat_lng = $request->lng_alamat_pemilik_lapangan;
        $lapangan->harga_per_jam = $request->harga_lapangan_per_jam;
        $lapangan->jumlah_court = $request->jumlah_court_pemilik_lapangan;

        if ($request->hasFile('foto_lapangan_1')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_1 = $request->file('foto_lapangan_1')->storeAs(
                $fotoLapanganPath, "foto_lapangan_1.jpg", 'public'
            );
            $lapangan->foto_lapangan_1 = $pathFotoLapangan_1;
        }
        if ($request->hasFile('foto_lapangan_2')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_2 = $request->file('foto_lapangan_2')->storeAs(
                $fotoLapanganPath, "foto_lapangan_2.jpg", 'public'
            );
            $lapangan->foto_lapangan_2 = $pathFotoLapangan_2;
        }
        if ($request->hasFile('foto_lapangan_3')) {
            $userPath = 'file/'.$user->id.'/';
            Storage::disk('public')->makeDirectory($userPath);
            $fotoLapanganPath = $userPath.$request->nama_lapangan_pemilik_lapangan;
            Storage::disk('public')->makeDirectory($fotoLapanganPath);
            $pathFotoLapangan_3 = $request->file('foto_lapangan_3')->storeAs(
                $fotoLapanganPath, "foto_lapangan_3.jpg", 'public'
            );
            $lapangan->foto_lapangan_3 = $pathFotoLapangan_3;
        }

        $lapangan->save();

        $dataLapangan = Lapangan::find($lapangan->id);
        
        $statusLapanganArr = array();
        $lapanganBuka = strtotime($dataLapangan->buka_dari_jam);
        $lapanganTutup = strtotime($dataLapangan->buka_sampai_jam);

        for($court= 1; $court <= $lapangan->jumlah_court; $court++){
            for($jam=$lapanganBuka; $jam<$lapanganTutup; $jam+=3600){
                array_push($statusLapanganArr, array(
                    'id_lapangan' => $lapangan->id,
                    'court' => $court,
                    'status' => 'Available',
                    'jam_status_berlaku_dari' => date('H:i', $jam),
                    'jam_status_berlaku_sampai' => date('H:i', $jam + 3600)
                ));
            }
        }

        // dd($statusLapanganArr);
        
        StatusLapangan::insert($statusLapanganArr);
    }

    
    public function getPenyewaLapanganProfil($penggunaPenyewaId, $date, $pembayaranId){
        $dataProfilPenyewa = DB::table('tb_pengguna')->select('tb_booking.tgl_booking', 'tb_booking.jam_mulai', 'tb_booking.jam_selesai', 'tb_booking.court', 
            'tb_pengguna.id as pengguna_id', 'tb_pengguna.name', 'tb_pembayaran.total_biaya', 'tb_pembayaran.id AS pembayaran_id', 'tb_riwayat_status_pembayaran.status_pembayaran')
            ->leftJoin('tb_booking', 'tb_booking.id_pengguna', '=', 'tb_pengguna.id')
            ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
            ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran GROUP BY tb_riwayat_status_pembayaran.id_pembayaran)');
            })
            ->where('tb_booking.id_pengguna', $penggunaPenyewaId)->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($date)))->where('tb_booking.id_pembayaran', $pembayaranId)
            ->get();
            // ->where('tb_riwayat_status_pembayaran.status_pembayaran', '!=', 'Batal')
        return response()->json($dataProfilPenyewa);
    }

    public function penyewaLapanganProfil(){
        $dataUser = User::select('name', 'email', 'nomor_telepon')->find(Auth::user()->id);
        
        return view('penyewa_lapangan.penyewa_lapangan_profil', compact('dataUser'));
    }
    
}
