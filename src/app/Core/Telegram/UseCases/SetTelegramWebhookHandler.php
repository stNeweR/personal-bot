<?php

namespace App\Core\Telegram\UseCases;

use App\Core\Telegram\Exceptions\SetWebhookException;
use App\Core\Telegram\Services\Telegram\TelegramApiService;

final readonly class SetTelegramWebhookHandler
{
    public function __construct(
        private TelegramApiService $telegramWebhookService,
    ) {}

    /**
     * @throws SetWebhookException
     */
    public function handle(): bool
    {
        $result = $this->telegramWebhookService->setWebhook();

        if (is_null($result->error_code) && $result->ok) {
            return $result->ok;
        }

        throw new SetWebhookException($result->description, $result->error_code);
    }
}
