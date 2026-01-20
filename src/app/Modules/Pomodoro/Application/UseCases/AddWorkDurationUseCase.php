<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\AddWorkDurationDTO;
use App\Modules\Pomodoro\Infrastructure\Repository\PomodoroSettingsRepository;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Adapters\UserAdapter;
use Illuminate\Support\Facades\Log;

final readonly class AddWorkDurationUseCase
{
    public function __construct(
        private PomodoroSettingsRepository $pomodoroSettingsRepository,
        private UserAdapter $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(AddWorkDurationDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $this->pomodoroSettingsRepository->create($user->id, (int) $data->message);

        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_BREAK_DURATION);

        $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили рабочее время. Теперь введите время перерыва');
    }
}
