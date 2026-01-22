<?php

namespace App\Modules\User\Infrastructure\Adapters;

use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Models\User;
use App\Modules\User\Infrastructure\Models\UserState;
use App\Modules\User\Infrastructure\Repository\UserRepository;
use App\Modules\User\Infrastructure\Repository\UserStateRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final class UserAdapter implements UserAdapterInterface
{
    public function __construct(
        public readonly UserRepository $userRepository,
        public readonly UserStateRepository $userStateRepository
    ) {}

    public function updateUserState(int $telegramId, UserStateValue $stateValue): UserState
    {
        $this->userStateRepository->clearUserStatesByTelegramId($telegramId);

        return $this->userStateRepository->createByTelegramId($telegramId, $stateValue);
    }

    /**
     * @throws ModelNotFoundException
     */
    public function getUserByTelegramId(int $telegramId): User
    {
        return $this->userRepository->getByTelegramId($telegramId);
    }

    public function clearUserState(int $telegramId): int
    {
        return $this->userStateRepository->clearUserStatesByTelegramId($telegramId);
    }
}
