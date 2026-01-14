<?php

namespace App\Core\Telegram\Contracts;

use App\Core\Telegram\Services\Telegram\DTOs\TelegramApiResponse;

interface TelegramApiClientInterface
{
    public function setWebhook(): TelegramApiResponse;
}
