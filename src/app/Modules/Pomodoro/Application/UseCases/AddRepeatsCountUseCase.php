<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Modules\Pomodoro\Infrastructure\Repository\PomodoroSettingsRepository;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Adapters\UserAdapter;

final readonly class AddRepeatsCountUseCase
{
    public function __construct(
        private PomodoroSettingsRepository $pomodoroSettingsRepository,
        private UserAdapter $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $this->pomodoroSettingsRepository->update($user->id, 'repeats_count', (int) $data->message);

        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_LONG_BREAK_DURATION);

        $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили количество повторов. Теперь введите длительность длинного перерыва');
    }
}
