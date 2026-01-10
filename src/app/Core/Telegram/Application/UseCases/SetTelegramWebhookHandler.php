<?php

namespace App\Core\Telegram\Application\UseCases;

use App\Core\Telegram\Domain\Exceptions\SetWebhookException;
use App\Core\Telegram\Infrastructure\Http\TelegramApiClient;

final readonly class SetTelegramWebhookHandler
{
    public function __construct(
        private TelegramApiClient $telegramWebhookService,
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
