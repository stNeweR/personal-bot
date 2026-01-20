<?php

namespace App\Core\Telegram\Application\DTOs;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapName(SnakeCaseMapper::class)]
final class TelegramUpdateDTO extends Data
{
    public function __construct(
        public int $updateId,
        public ?int $userId,
        public ?int $chatId,
        public ?string $messageText,
        public ?string $callbackData
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            updateId: $data['update_id'] ?? 0,
            userId: $data['message']['from']['id'] ?? $data['callback_query']['from']['id'] ?? null,
            chatId: $data['message']['chat']['id'] ?? $data['callback_query']['message']['chat']['id'] ?? null,
            messageText: $data['message']['text'] ?? null,
            callbackData: $data['callback_query']['data'] ?? null
        );
    }

    public function getCommand(): ?string
    {
        if (! $this->messageText) {
            return null;
        }

        $text = trim($this->messageText);

        if (! str_starts_with($text, '/')) {
            return null;
        }

        $parts = explode(' ', $text, 2);
        $commandWithBot = $parts[0];

        $command = explode('@', $commandWithBot)[0];

        return strtolower(substr($command, 1));
    }
}
