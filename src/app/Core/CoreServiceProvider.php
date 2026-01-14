<?php

namespace App\Core;

use App\Core\Telegram\Console\SetTelegramWebhookCommand;
use Illuminate\Support\ServiceProvider;

final class CoreServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetTelegramWebhookCommand::class,
            ]);
        }
    }
}
