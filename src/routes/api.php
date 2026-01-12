<?php

use App\Core\Telegram\Presentation\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('telegram-webhook', [TelegramWebhookController::class, 'handleWebhook']);
