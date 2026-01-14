<?php

namespace App\Core\Telegram\Http\V1\Controllers;

use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Http\V1\Requests\TelegramWebhookRequest;
use App\Core\Telegram\UseCases\TelegramWebhookUpdateHandler;

final class TelegramWebhookController
{
    public function handleWebhook(TelegramWebhookRequest $request, TelegramWebhookUpdateHandler $handler): void
    {
        $handler->handle(
            TelegramUpdateDTO::fromArray($request->validated())
        );
    }
}
