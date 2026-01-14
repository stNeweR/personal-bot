<?php

namespace App\Core\Telegram\Infrastructure\Console;

use App\Core\Telegram\Application\UseCases\SetTelegramWebhookHandler;
use App\Core\Telegram\Domain\Exceptions\SetWebhookException;
use Illuminate\Console\Command;

final class SetTelegramWebhookCommand extends Command
{
    protected $signature = 'telegram:set-webhook';

    protected $description = 'Set telegram webhook';

    private string $text = 'Webhook set!';

    public function handle(SetTelegramWebhookHandler $handler): int
    {
        try {
            $handler->handle();

            $this->info($this->text);

            return self::SUCCESS;
        } catch (SetWebhookException $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
