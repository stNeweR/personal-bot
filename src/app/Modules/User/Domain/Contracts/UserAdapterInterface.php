<?php

namespace App\Modules\User\Domain\Contracts;

use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Models\User;
use App\Modules\User\Infrastructure\Models\UserState;

interface UserAdapterInterface
{
    public function getUserByTelegramId(int $telegramId): User;

    public function updateUserState(int $userId, UserStateValue $stateValue): UserState;
}
