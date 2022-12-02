<?php

use Illuminate\Http\Request;
use SimpleBotAPI\TelegramBot;
use SimpleBotAPI\UpdatesHandler;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/bot-webhook', 'TelegramController@telegramWebhook');

// Route::post('/bot-webhook', function () {
//     $Bot = new TelegramBot(env('TELEGRAM_BOT_TOKEN'), new MyBot());
//     $Bot->OnWebhookUpdate();
// })->middleware('api');

