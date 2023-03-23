<?php

namespace App\Http\Controllers;

use App\Models\Kupon;
use App\Models\Lapangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class KuponController extends Controller
{
    public function kuponDashboard() {
        return view('pemilik_lapangan.pemilik_lapangan_kupon_dashboard');
    }

    public function checkKupon(Request $request) {
        $dateNow = date('Y-m-d');

        $kupons= Kupon::where('id_lapangan', $request->lapanganId)
            ->where('kode_kupon', $request->kode_kupon)
            ->whereRaw('tb_kupon.`tgl_berlaku_dari` <= "'.$dateNow.'" AND tb_kupon.`tgl_berlaku_sampai` >= "'.$dateNow.'"')
            ->first();

        if($kupons) {
            $response = [
                'potongan_diskon' => $kupons->total_diskon_persen,
                'kode_promo' => $kupons->kode_kupon
            ];

            return $response;
        }

        return response()->json('Kupon Tidak Tersedia', 204);
    }

    public function getDataKuponLapangan(Request $request) {
        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length");

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $totalRecords = Kupon::where('id_lapangan', $lapanganId->id)
            ->count();

        $dataKuponLapangan = Kupon::select('id AS kupon_id', 'total_diskon_persen', 'kode_kupon', 'tgl_berlaku_dari', 'tgl_berlaku_sampai', 'status_delete')
            ->where('id_lapangan', $lapanganId->id)
            ->orderBy('tb_kupon.id', 'DESC')
            ->get();

        $response = array (
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecords,
            "aaData" => $dataKuponLapangan
        );

        return response()->json($response);
    }

    public function createManajemenKuponLapangan(Request $request) {
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataKuponLapangan = new Kupon;
        $dataKuponLapangan->id_lapangan = $lapanganId->id;
        $dataKuponLapangan->kode_kupon = $request->kode_kupon;
        $dataKuponLapangan->total_diskon_persen = $request->diskon_persen;
        $dataKuponLapangan->tgl_berlaku_dari = date("Y-m-d", strtotime($request->tgl_kupon_dari));
        $dataKuponLapangan->tgl_berlaku_sampai = date("Y-m-d", strtotime($request->tgl_kupon_sampai));
        $dataKuponLapangan->status_delete = 0;
        $dataKuponLapangan->save();

        return response()->json('success');
    }

    public function editManajemenKuponLapangan(Request $request) {
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataKuponLapangan = Kupon::select('id AS kupon_id', 'total_diskon_persen', 'kode_kupon', 'tgl_berlaku_dari', 'tgl_berlaku_sampai', 'status_delete')
            ->where('id_lapangan', $lapanganId->id)
            ->where('id', $request->kupon_id)
            ->first();

        return response()->json($dataKuponLapangan);
    }

    public function updateManajemenKuponLapangan(Request $request) {
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataKuponLapangan = Kupon::where('id', $request->kupon_id)->first();

        $dataKuponLapangan->tgl_libur_dari = date("Y-m-d", strtotime($request->tgl_libur_dari));
        $dataKuponLapangan->tgl_libur_sampai = date("Y-m-d", strtotime($request->tgl_libur_sampai));
        $dataKuponLapangan->save();

        return response()->json('success', 200);
    }

    public function destroyManajemenKuponLapangan(Request $request) {
        $lapanganId = Lapangan::select('tb_lapangan.id')->with('User')->where('tb_lapangan.id_pengguna', Auth::user()->id)->first();

        $dataKuponLapangan = Kupon::where(['tb_lapangan_libur.id' => $request->libur_lapangan_id, 'tb_lapangan_libur.id_lapangan' => $lapanganId->id])->first();
        $dataKuponLapangan->delete();

        return response()->json('success', 200);
    }
}
