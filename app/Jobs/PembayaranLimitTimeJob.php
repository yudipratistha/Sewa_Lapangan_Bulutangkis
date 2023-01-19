<?php

namespace App\Jobs;

use Carbon\Carbon;

use App\Models\Pesan;
use App\Models\Pembayaran;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Models\RiwayatStatusPembayaran;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class PembayaranLimitTimeJob implements ShouldQueue
{
    protected $pembayaran;
    protected $pesan;
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pembayaran $pembayaran, Pesan $pesan)
    {
        $this->pembayaran = $pembayaran;
        $this->pesan = $pesan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pembayaran = $this->pembayaran;
        $pesan = $this->pesan;

        $pembayaranCreated = date('H:i:s', strtotime($pembayaran->created_at));
        $pembayaranTimeLimit = date('H:i:s', strtotime('+1 hour', strtotime($pembayaran->created_at)));

        $status = true;
        while($status){
            echo $pembayaranTimeLimit.' ';
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
            }

            if(date('H:i:s', strtotime('+15 minute', strtotime($timeNow))) === $pembayaranTimeLimit){
                $pesan = $this->pesan;
                $sendto = env('TELEGRAM_API_URL').env('TELEGRAM_BOT_TOKEN')."/sendmessage?chat_id=".$pesan->chat_id."&text=".$pesan->pesan."&parse_mode=html";
                file_get_contents($sendto);
                echo "Message was sent to ".$sendto."\n";
            }
        }
    }
}
