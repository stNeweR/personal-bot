<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Modules\Pomodoro\Infrastructure\Repository\PomodoroSettingsRepository;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Adapters\UserAdapter;

final readonly class AddLongBreakDurationUseCase
{
    public function __construct(
        private PomodoroSettingsRepository $pomodoroSettingsRepository,
        private UserAdapter $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $this->pomodoroSettingsRepository->update($user->id, 'long_break_duration', (int) $data->message);

        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_CYCLES_BEFORE_LONG_BREAK);

        $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили длительность длинного перерыва. Теперь введите количество циклов до длинного перерыва');
    }
}
