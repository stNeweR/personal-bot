<?php

namespace App\Core\Telegram\UseCases;

use App\Core\Telegram\Application\DTOs\TelegramUpdateDTO;
use App\Core\Telegram\Application\Handlers\Command\HandlerDispatcher;

final readonly class TelegramWebhookUpdateHandler
{
    public function __construct(
        public HandlerDispatcher $handlerDispatcher,
    ) {}

    public function handle(TelegramUpdateDTO $data): void
    {
        $this->handlerDispatcher->dispatch($data);
    }
}
