<?php

namespace App\Modules\User\Domain\Repository;

use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Models\UserState;

interface UserStateRepositoryInterface
{
    public function clearUserStatesByTelegramId(int $telegramId): int;

    public function createByTelegramId(int $telegramId, UserStateValue $stateValue): UserState;
}
