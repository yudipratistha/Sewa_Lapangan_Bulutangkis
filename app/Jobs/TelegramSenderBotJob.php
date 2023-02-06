<?php

namespace App\Jobs;

use App\Models\Pesan;

use App\Models\Pembayaran;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class TelegramSenderBotJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $pembayaran;
    protected $pesanToPemilik;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Pembayaran $pembayaran, Pesan $pesanToPemilik)
    {
        $this->pembayaran = $pembayaran;
        $this->pesanToPemilik = $pesanToPemilik;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pesanToPemilik = $this->pesanToPemilik;
        $sendto = env('TELEGRAM_API_URL').env('TELEGRAM_BOT_TOKEN')."/sendmessage?chat_id=".$pesanToPemilik->chat_id."&text=".$pesanToPemilik->pesan."&parse_mode=html";
        file_get_contents($sendto);
        echo "Message was sent to ".$pesanToPemilik->chat_id."\n";
    }
}
