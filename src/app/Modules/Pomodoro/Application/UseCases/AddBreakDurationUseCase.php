<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Adapters\UserAdapter;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Infrastructure\Repository\PomodoroSettingsRepository;

final readonly class AddBreakDurationUseCase
{
    public function __construct(
        private PomodoroSettingsRepository $pomodoroSettingsRepository,
        private UserAdapter $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $this->pomodoroSettingsRepository->update($user->id, 'break_duration', (int) $data->message);

        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_REPEATS_COUNT);

        $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно время перерыва. Теперь введите количество повторов');
    }
}
