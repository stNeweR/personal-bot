<?php

namespace App\Core\Telegram\Infrastructure\Http\V1\Controllers;

use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Application\UseCases\TelegramWebhookUpdateUseCase;
use App\Core\Telegram\Infrastructure\Http\V1\Requests\TelegramWebhookRequest;

final class TelegramWebhookController
{
    public function handleWebhook(TelegramWebhookRequest $request, TelegramWebhookUpdateUseCase $handler): void
    {
        $handler->execute(
            TelegramUpdateDTO::fromArray($request->validated())
        );
    }
}
