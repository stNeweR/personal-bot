<?php

namespace App\Modules\User\Domain\Repository;

use App\Modules\User\Infrastructure\Models\User;

interface UserRepositoryInterface
{
    public function createUser(string $telegramId): User;

    public function getByTelegramId(int $telegramId): User;
}
