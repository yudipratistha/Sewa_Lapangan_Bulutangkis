<?php

namespace App\Jobs;

use App\Models\Pembayaran;
use App\Models\RiwayatStatusPembayaran;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PembayaranLimitTimeJob implements ShouldQueue
{
    protected $pembayaran;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pembayaran $pembayaran)
    {
        $this->pembayaran = $pembayaran;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pembayaran = $this->pembayaran;

        $pembayaranCreated = date('H:i:s', strtotime($pembayaran->created_at));
        $pembayaranTimeLimit = date('H:i:s', strtotime('+1 hour', strtotime($pembayaran->created_at)));
        $status = true;
        while($status){
            sleep(1);
            $now = Carbon::now('Asia/Singapore');
            $now->addMinute(0);
            $timeNow  = $now->format('H:i:s');

            if($timeNow > $pembayaranCreated && $timeNow > $pembayaranTimeLimit){
                $pembayaranGetBukti = Pembayaran::find($this->pembayaran->id);

                if(!isset($pembayaranGetBukti->foto_bukti_pembayaran)){
                    $riwayatPembayaranStatus = new RiwayatStatusPembayaran;
                    $riwayatPembayaranStatus->id_pembayaran = $this->pembayaran->id;
                    $riwayatPembayaranStatus->status_pembayaran = 'Batal';
                    $riwayatPembayaranStatus->save();
                }

                $status = false;
            // }else{
            //     echo $timeNow . '\n';
            //     // continue;
            }
        }
    }
}
