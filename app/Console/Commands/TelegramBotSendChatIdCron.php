<?php

namespace App\Console\Commands;

use SimpleBotAPI\TelegramBot;
use SimpleBotAPI\BotSettings;
use SimpleBotAPI\UpdatesHandler;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TelegramBotSendChatIdCron extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'TelegramBotSendChatId:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // date_default_timezone_set('Asia/Jakarta');
        // define('BOT_TOKEN', '5685008986:AAFHjeZkWnRWKlbZUvhmZGYSwimEpx73GEY');
        // define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');

        // $host = "localhost";
        // $user = "root";
        // $pass = "";
        // $db = "db_lapangan";

        // $conn = new mysqli($host, $user, $pass, $db);

        // $n_sec = 60;
        // $sec = 0;
        // $starting_time = microtime(true);
        // // send reply
        // while($sec <= $n_sec){
        //     $time_start = microtime(true);

        //     $sql = "SELECT * FROM tb_pesan WHERE status_pesan = '0' LIMIT 10";
        //     $qry = $conn->query($sql);
        //     $rows = array();
        //     $rows_id = array();
        //     while($data = $qry->fetch_assoc()){
        //         $rows[] = $data;
        //         $rows_id[] = $data['id'];
        //     }
        //     $sql2 = "UPDATE tb_pesan SET status_pesan='1' WHERE id in (".join(",",$rows_id).")";
        //     $qry2 = $conn->query($sql2);

        //     if($qry->num_rows) {
        //         foreach($rows as $data){
        //             $sendto =API_URL."sendmessage?chat_id=".$data['chat_id']."&text=".$data['pesan']."&parse_mode=html";
        //             file_get_contents($sendto);
        //             echo "Message was sent to ".$data['chat_id']."\n";

        //             $sql2 = "UPDATE tb_pesan SET out_date='".date('Y-m-d H:i:s')."' WHERE id = ".$data['id'];
        //             $qry2 = $conn->query($sql2);
        //         }
        //     } else {
        //         echo "No new message to sent..\n";
        //         sleep(1);
        //     }

        //     $time_end = microtime(true);
        //     $execution_time = ($time_end - $time_start);

        //     //execution time of the script
        //     echo '10 Qry Execution Time: '.$execution_time." sec\n";
        //     $sec+=$execution_time;

        //     if(($time_end - $starting_time) >= $n_sec) break;
        // }
        // echo 'Total Execution Time: '.$sec." sec\n";

    }
}
