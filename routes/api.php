<?php

use App\Http\Controllers\Api\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Jalur API untuk penerimaan Webhook dari Telegram Bot.
|
*/

Route::post('/webhooks/telegram', [TelegramWebhookController::class, 'handle']);
