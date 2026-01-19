<?php

namespace App\Modules\User;

use App\Core\Telegram\Infrastructure\Adapters\UserTelegramAdapter;
use App\Modules\User\Domain\Contracts\TelegramAdapterInterface;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            TelegramAdapterInterface::class,
            UserTelegramAdapter::class
        );
    }
}
