<?php

namespace App\Modules\User\Infrastructure\Repository;

use App\Modules\User\Domain\Repository\UserRepositoryInterface;
use App\Modules\User\Infrastructure\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(string $telegramId): User
    {
        return User::query()
            ->createOrFirst([
                'telegram_id' => $telegramId,
            ]);
    }

    public function getByTelegramId(int $telegramId): ?User
    {
        return User::query()
            ->firstWhere('telegram_id', $telegramId);
    }
}
