<?php

namespace App\Core\Telegram\Infrastructure\Http\DTOs;

use Spatie\LaravelData\Data;

final class TelegramApiResponse extends Data
{
    public function __construct(
        public readonly bool $ok,
        public readonly string $description,
        public readonly ?bool $result,
        public readonly ?int $error_code,
    ) {}
}
