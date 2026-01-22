<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Modules\Pomodoro\Infrastructure\Repository\PomodoroSettingsRepository;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Infrastructure\Adapters\UserAdapter;

final readonly class AddWorkDurationUseCase
{
    public function __construct(
        private PomodoroSettingsRepository $pomodoroSettingsRepository,
        private UserAdapter $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

        $settings = $this->pomodoroSettingsRepository->getByUserId($user->id);

        if (is_null($settings)) {
            $this->pomodoroSettingsRepository->create($user->id, (int) $data->message);

            $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили рабочее время. Теперь введите время перерыва');
        } else {
            $this->pomodoroSettingsRepository->update($user->id, 'work_duration', (int) $data->message);

            $this->telegramAdapter->sendMessage($user->telegram_id, 'У вас уже была настройка для помодоро таймера. Рабочее время обновлено. Теперь введите время перерыва');
        }
        $this->userAdapter->updateUserState($user->telegram_id, UserStateValue::AWAITING_BREAK_DURATION);
    }
}
