<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Domain\Contracts\TelegramAdapterInterface;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSettingsRepositoryInterface;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use App\Modules\User\Domain\Enums\UserStateValue;

final readonly class AddBreakDurationUseCase
{
    public function __construct(
        private PomodoroSettingsRepositoryInterface $pomodoroSettingsRepository,
        private UserAdapterInterface $userAdapter,
        private TelegramAdapterInterface $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $this->pomodoroSettingsRepository->update($user->id, 'break_duration', (int) $data->message);

        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_REPEATS_COUNT);

        $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили время перерыва. Теперь введите количество повторов');
    }
}
