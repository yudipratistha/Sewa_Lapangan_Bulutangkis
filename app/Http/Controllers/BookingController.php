<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Lapangan;
use App\Models\StatusLapangan;
use App\Models\Pembayaran;

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
        
        if($request->tglBooking <= $currentDate){
            $dataBookArr = array();

            $totalJamBookingLapangan = Lapangan::select('harga_per_jam')->where('id', json_decode($request->checkBook[0])->lapangan_id)->first();
            $totalHargaBookingLapangan = count($request->checkBook) * $totalJamBookingLapangan->harga_per_jam;

            $pembayaran = new Pembayaran;
            $pembayaran->total_biaya = $totalHargaBookingLapangan;
            $pembayaran->status = 'Belum Lunas';
            $pembayaran->save();

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
        
        // $job = DB::table('jobs')->whereId(88)->first();
        // $payload = json_decode($job->payload);
        // $mailable = unserialize($payload->data->command);
        // dd($mailable);
        // if ($mailable->user->id != GOOD)
        //     DB::table('jobs')->whereId($id)->delete();
    }
}
