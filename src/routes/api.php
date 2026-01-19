<?php

use App\Core\Telegram\Infrastructure\Http\V1\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('telegram-webhook', [TelegramWebhookController::class, 'handleWebhook']);
