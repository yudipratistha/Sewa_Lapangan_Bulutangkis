<?php

namespace App\Jobs;

use App\Models\Pesan;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TelegramSenderBotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $pesan;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pesan $pesan)
    {
        $this->pesan = $pesan;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pesan = $this->pesan;
        print_r($pesan->chat_id);
        $status = true;
        while($status){
            $pesanId = array();
            sleep(1);
            $pesanQueues = Pesan::where('status_pesan', '0')->take(50)->get();

            if(isset($pesanQueues)){
                foreach($pesanQueues as $pesanQueue){
                    $pesanId[] = $pesanQueue->id;

                    $sendto = env('TELEGRAM_API_URL').env('TELEGRAM_BOT_TOKEN')."/sendmessage?chat_id=".$pesanQueue->chat_id."&text=".$pesanQueue->pesan."&parse_mode=html";
                    file_get_contents($sendto);
                    echo "Message was sent to ".$pesanQueue->chat_id."\n";

                    Pesan::where('id', $pesanQueue->id)->update(['out_date' => date('Y-m-d H:i:s')]);
                }
                print_r($pesanId);
                Pesan::whereIn('id', $pesanId)->update(['status_pesan' => '1']);
            }else{
                echo "No new message to sent..\n";
            }
        }
    }
}
