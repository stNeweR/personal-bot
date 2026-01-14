<?php

namespace App\Modules\User\Domain\Repository;

interface UserRepositoryInterface
{
    public function createUser(string $telegramId);

    public function getByTelegramId(int $telegramId);
}
