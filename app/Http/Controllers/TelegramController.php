<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;;

class Telebot
{
    private $update;
    private $tasks = [];

    public $token;
    public $apiURL;

    public function __construct(string $token)
    {
        $this->token = $token;
        $this->apiURL = "https://api.telegram.org/bot" . $this->token;
    }

    private function createContext($update)
    {
        return new class($this->apiURL, $update)
        {
            public
                $apiURL,
                $update,
                $updateId,
                $message,
                $messageId,
                $from,
                $chat,
                $chatId,
                $date,
                $text;

            public function __construct($apiURL, $update)
            {
                $this->apiURL = $apiURL;
                $this->update = $update;
                $this->updateId = $update->update_id;
                if ($update->message != null) {
                    $this->message = $update->message;
                    $this->messageId = $update->message->message_id;
                    $this->from = $update->message->from;
                    $this->chat = $update->message->chat;
                    $this->chatId = $update->message->chat->id;
                    $this->date = $update->message->date;
                    $this->text = $update->message->text;
                }
            }

            public function replyWithText(string $text, array $options = [])
            {
                $data["chat_id"] = $this->chatId;
                $data["text"] = $text;

                if (array_key_exists("reply_to_message_id", $options)) {
                    $data["reply_to_message_id"] = $options["reply_to_message_id"];
                }

                if (array_key_exists("parse_mode", $options)) {
                    if (in_array($options["parse_mode"], ["Markdown", "MarkdownV2", "HTML"])) {
                        $data["parse_mode"] = $options["parse_mode"];
                    }
                }
                $queries = http_build_query($data);
                file_get_contents($this->apiURL . "/sendMessage?$queries");
            }
        };
    }

    public function command(string $command, callable $callback)
    {
        $task = [
            "args" => [$command, $callback],
            "do" =>  function (string $command, callable $callback) {
                if ($this->update == null) return;

                $ctx = $this->createContext($this->update);

                if ($ctx->message != null) {
                    if (strpos($ctx->text, "/$command") === 0) {
                        $callback($ctx);
                    }
                }
            }
        ];
        array_push($this->tasks, $task);
    }

    public function run()
    {
        $json = file_get_contents('php://input');
        $this->update = json_decode($json);

        foreach ($this->tasks as $task) {
            $task["do"](...$task["args"]);
        }
    }

}

class TelegramController extends Controller
{
    public function telegramWebhook(Request $request){
        $bot = new Telebot("5685008986:AAFHjeZkWnRWKlbZUvhmZGYSwimEpx73GEY");

        // handle start command
        $bot->command("start", function ($ctx) {
            $ctx->replyWithText("Selamat datang di bot Telegram Sistem Informasi Penyewaan Lapangan Bulu Tangkis. Telegram ID anda untuk bot ini adalah ". $ctx->chatId . ". Silahkan masukkan Telegram ID tersebut pada halaman profil anda untuk menerima pesan otomatis dari bot Telegram. Terima kasih!");
        });

        // handle hello command
        $bot->command("hello", function ($ctx) {
            $ctx->replyWithText("Halo kak " . $ctx->chatId);
        });

        // run bot
        $bot->run();
    }
}
