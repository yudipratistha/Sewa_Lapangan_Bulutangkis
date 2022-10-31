<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CheckVerifField
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
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
            return redirect(route('pemilik_lapangan.belumDiverif'));
        }else if($dataLapanganVerif->status_verifikasi === 'ditolak'){
            return redirect(route('pemilik_lapangan.verifikasiDitolak'));
        }

        return $next($request);
    }
}
