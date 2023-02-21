<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{

    public function __construct(){
        $this->middleware('auth');
    }

    public function getFileBuktiPembayaran($id_pembayaran){
        $dataPembayaran = DB::table('tb_pembayaran')->select('tb_lapangan.nama_lapangan', 'tb_booking.tgl_booking', 'tb_booking.id_pengguna AS id_pengguna_booking',
            'tb_booking.tgl_booking', 'tb_pembayaran.id AS id_pembayaran', 'tb_pembayaran.foto_bukti_pembayaran')
            ->leftJoin('tb_booking', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
            ->leftJoin('tb_courts', 'tb_courts.id', '=', 'tb_booking.id_court')
            ->leftJoin('tb_lapangan', 'tb_lapangan.id', '=', 'tb_courts.id_lapangan')
            ->where('tb_pembayaran.id', $id_pembayaran)
            ->where('tb_lapangan.id_pengguna', Auth::user()->id)
            ->first();

        if(isset($dataPembayaran->foto_bukti_pembayaran)){
            $namaFile = explode('/', $dataPembayaran->foto_bukti_pembayaran)[7];

            $fullpath="app/bukti_bayar/".strtolower(str_replace(' ', '_', $dataPembayaran->nama_lapangan))."/{$dataPembayaran->id_pengguna_booking}/{$dataPembayaran->tgl_booking}/{$dataPembayaran->id_pembayaran}/{$namaFile}";

            return response()->download(storage_path($fullpath), null, [], null);
        }
        return response()->json('Not Found', 404);
    }
}
