<?php

namespace App\Core\Telegram\Domain\Contracts;

use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\TelegramApiResponse;

interface TelegramApiClientInterface
{
    public function setWebhook(): TelegramApiResponse;
}
