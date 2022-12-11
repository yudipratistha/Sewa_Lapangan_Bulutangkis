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
        $sendto = env('TELEGRAM_API_URL').env('TELEGRAM_BOT_TOKEN')."/sendmessage?chat_id=".$pesan->chat_id."&text=".$pesan->pesan."&parse_mode=html";
        file_get_contents($sendto);
        echo "Message was sent to ".$pesan->chat_id."\n";

        Pesan::where('id', $pesan->id)->update(['updated_at' => date('Y-m-d H:i:s')]);
    }
}
