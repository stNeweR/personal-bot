<?php

namespace App\Modules\User\Infrastructure\Repository;

use App\Modules\User\Domain\Models\User;
use App\Modules\User\Domain\Repository\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function createUser(string $telegramId): User
    {
        return User::query()
            ->createOrFirst([
                'telegram_id' => $telegramId
            ]);
    }
}
