<?php

namespace App\Core\Telegram\Presentation\Http\Controllers;

use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Application\UseCases\TelegramWebhookUpdateHandler;
use App\Core\Telegram\Presentation\Http\Requests\TelegramWebhookRequest;

final class TelegramWebhookController
{
    public function handleWebhook(TelegramWebhookRequest $request, TelegramWebhookUpdateHandler $handler): void
    {
        $handler->handle(
            TelegramUpdateDTO::fromArray($request->validated())
        );
    }
}
