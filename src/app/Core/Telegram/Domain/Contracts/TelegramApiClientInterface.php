<?php

namespace App\Core\Telegram\Domain\Contracts;

use App\Core\Telegram\Infrastructure\Http\DTOs\TelegramApiResponse;

interface TelegramApiClientInterface
{
    public function setWebhook(): TelegramApiResponse;
}
