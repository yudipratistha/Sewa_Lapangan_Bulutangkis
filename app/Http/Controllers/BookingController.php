<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\StatusLapangan;
use App\Models\Pembayaran;
use App\Models\RiwayatStatusPembayaran;

use App\Services\Midtrans\CreateSnapTokenService;
use App\Jobs\PembayaranLimitTimeJob;
use App\Events\PembayaranLimitTime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function storeBookingLapangan(Request $request){
        $currentDate = date('d-m-Y');
        $errorTextJamBooking = '';
        $errorTextPembayaran = '';
        
        // if(!isset($request->checkBook)) dd($request->checkBook);
        
        if($request->tglBooking <= $currentDate){
            $dataBookArr = array();

            $dataLapangan = DB::table('tb_booking')->select('tb_lapangan.harga_per_jam', 'tb_riwayat_status_pembayaran.status_pembayaran')
                ->leftJoin('tb_lapangan', 'tb_booking.id_lapangan', '=', 'tb_lapangan.id')
                ->leftJoin('tb_pembayaran', 'tb_booking.id_pembayaran', '=', 'tb_pembayaran.id')
                ->leftJoin('tb_riwayat_status_pembayaran', function($join){
                    $join->on('tb_riwayat_status_pembayaran.id_pembayaran', '=', 'tb_pembayaran.id')
                    ->whereRaw('tb_riwayat_status_pembayaran.id IN (SELECT MAX(tb_riwayat_status_pembayaran.id) FROM tb_riwayat_status_pembayaran)');
                })
                ->where('tb_lapangan.id', $request->lapanganId)
                ->first();

            if($dataLapangan->status_pembayaran === 'Belum Lunas'){
                return response()->json(['error' => "Ada data pembayaran belum lunas"], 400);
            }else{
                if(isset($request->checkBook) && $request->pilihPembayaran !== "undefined"){
                    
                    $totalHargaBookingLapangan = count($request->checkBook) * $dataLapangan->harga_per_jam;

                    $pembayaran = new Pembayaran;
                    $pembayaran->id_daftar_jenis_pembayaran = $request->pilihPembayaran;
                    $pembayaran->total_biaya = $totalHargaBookingLapangan;
                    $pembayaran->save();

                    $riwayatStatusPembayaran = new RiwayatStatusPembayaran;
                    $riwayatStatusPembayaran->id_pembayaran = $pembayaran->id;
                    $riwayatStatusPembayaran->status_pembayaran = 'Belum Lunas';
                    $riwayatStatusPembayaran->save();

                    PembayaranLimitTimeJob::dispatch($pembayaran);

                    foreach($request->checkBook as $checkBookKey => $checkBookVal){
                        $dataBook = json_decode($checkBookVal);
                        $jam = explode(" - ", $dataBook->jam);
                        array_push($dataBookArr, array(
                            'id_pengguna' => Auth::user()->id,
                            'id_lapangan' => $dataBook->lapangan_id,
                            'id_pembayaran' => $pembayaran->id,
                            'jam_mulai' => $jam[0],
                            'jam_selesai' => $jam[1],
                            'court' => $dataBook->court,
                            'tgl_booking' => date('Y-m-d', strtotime($request->tglBooking))
                        ));
                    }
                    Booking::insert($dataBookArr);
                    
                    return response()->json($totalHargaBookingLapangan);
                }
                
                if(!isset($request->checkBook)){
                    $errorTextJamBooking = "Jam booking belum dipilih!";
                }
                if($request->pilihPembayaran === "undefined"){
                    $errorTextPembayaran = "Pilih pembayaran belum dipilih!";
                }
                
                if(isset($errorTextJamBooking) || isset($errorTextPembayaran)){
                    return response()->json(['errorTextJamBooking' => $errorTextJamBooking, 'errorTextPembayaran' => $errorTextPembayaran], 400);
                } 
                
            }
        }
    }

    public function bookValid(){

        $now = Carbon::now('Asia/Singapore');
        $now->addMinute(0);
        $time  = $now->format('H:i:s');
        $start = '19:20:00';
        $end   = '19:30:00';
        $pembayaran = new Pembayaran;
        $pembayaran->id_booking = 1;
        $pembayaran->total_biaya = 60000;
        $pembayaran->status = 'Belum Lunas';
        $pembayaran->save();

        PembayaranLimitTimeJob::dispatch($pembayaran);
        // Event::dispatch(new PembayaranLimitTime($pembayaran));
        if ($time >= $start && $time <= $end) {
            // dd($time);
        }
    }
}
