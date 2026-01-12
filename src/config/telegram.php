<?php

declare(strict_types=1);

use App\Modules\User\Application\Handlers\Command\StartCommandHandler;

return [
    'telegram_bot_token' => env('TELEGRAM_BOT_TOKEN'),

    'application_webhook_endpoint' => env('APPLICATION_WEBHOOK_ENDPOINT', ''),

    'telegram_url' => env('TELEGRAM_URL', ''),

    'commands_handler' => [
        'start' => StartCommandHandler::class,
    ],
];
