<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\Pembayaran;
use App\Models\StatusCourt;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
        $dataLapangan = Lapangan::with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataLapanganUpdate = Lapangan::find($dataLapangan->id);
        $dataLapanganUpdate->nama_lapangan = $request->nama_lapangan_pemilik_lapangan;
        $dataLapanganUpdate->alamat_lapangan = $request->alamat_tertulis_pemilik_lapangan;
        $dataLapanganUpdate->titik_koordinat_lat = $request->lat_alamat_pemilik_lapangan;
        $dataLapanganUpdate->titik_koordinat_lng = $request->lng_alamat_pemilik_lapangan;
        // $dataLapanganUpdate->harga_per_jam = $request->harga_lapangan_per_jam;

        $dataPemilikUpdate = User::find(Auth::user()->id);
        $dataPemilikUpdate->name = $request->nama_pemilik_lapangan;
        $dataPemilikUpdate->chat_id = $request->chat_id_pemilik_lapangan;
        $dataPemilikUpdate->nomor_telepon = $request->nomor_telepon_pemilik_lapangan;


        

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
        $dataPemilikUpdate->save();

        return response()->json('success');
    }


    public function getPenyewaLapanganProfil($penggunaPenyewaId, $date, $pembayaranId){
        $getPenyewaLapanganInvoice = DB::table('tb_pengguna')->select('tb_lapangan.id AS lapangan_id', 'tb_lapangan.nama_lapangan', 'tb_lapangan.alamat_lapangan', 'tb_booking.tgl_booking', 'tb_detail_booking.jam_mulai', 'tb_detail_booking.jam_selesai', 'tb_courts.nomor_court', 'tb_detail_booking.harga_per_jam',
            'tb_pengguna.name AS nama_penyewa', 'tb_pengguna.nomor_telepon AS nomor_telepon_penyewa', 'tb_pembayaran.jenis_booking', 'tb_daftar_jenis_pembayaran.nama_jenis_pembayaran', 'tb_pembayaran.total_biaya', 'tb_pembayaran.id AS pembayaran_id', 'tb_riwayat_status_pembayaran.status_pembayaran')
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
            ->where('tb_booking.id_pengguna', $penggunaPenyewaId)
            // ->where('tb_booking.tgl_booking', date('Y-m-d', strtotime($date)))
            ->where('tb_booking.id_pembayaran', $pembayaranId)
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

    public function penyewaLapanganProfil(){
        $dataUser = User::select('name', 'email', 'nomor_telepon', 'chat_id')->find(Auth::user()->id);

        return view('penyewa_lapangan.penyewa_lapangan_profil', compact('dataUser'));
    }

    public function penyewaLapanganUpdateProfil(Request $request){

        $dataPenyewaUpdate = User::find(Auth::user()->id);
        $dataPenyewaUpdate->name = $request->nama_penyewa_lapangan;
        $dataPenyewaUpdate->nomor_telepon = $request->nomor_telepon_penyewa_lapangan;
        $dataPenyewaUpdate->chat_id = $request->chat_id_penyewa_lapangan;

        $dataPenyewaUpdate->save();

        $dataUser = User::select('name', 'email', 'nomor_telepon', 'chat_id')->find(Auth::user()->id);
        return view('penyewa_lapangan.penyewa_lapangan_profil', compact('dataUser'));

    }

}
