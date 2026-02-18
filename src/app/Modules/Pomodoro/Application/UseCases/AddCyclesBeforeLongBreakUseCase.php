<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Domain\Contracts\TelegramAdapterInterface;
use App\Modules\Pomodoro\Application\DTOs\UseCaseStateHandlerDTO;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSettingsRepositoryInterface;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;

final readonly class AddCyclesBeforeLongBreakUseCase
{
    public function __construct(
        private PomodoroSettingsRepositoryInterface $pomodoroSettingsRepository,
        private UserAdapterInterface $userAdapter,
        private TelegramAdapterInterface $telegramAdapter
    ) {}

    public function execute(UseCaseStateHandlerDTO $data): void
    {
        $user = $this->userAdapter->getUserByTelegramId($data->telegramId);
        $settings = $this->pomodoroSettingsRepository->getByUserId($user->id);

        if ($settings !== null && (int) $data->message >= $settings->repeats_count) {
            $this->telegramAdapter->sendMessage($user->telegram_id, 'Количество повторов до длинного перерыва должно быть меньше общего количества повторов. Введите ещё раз количество повторов до длинного перерыва');
        } else {
            $this->pomodoroSettingsRepository->update($user->id, 'cycles_before_long_break', (int) $data->message);

            $this->userAdapter->clearUserState($user->telegram_id);

            $this->telegramAdapter->sendMessage($user->telegram_id, 'Успешно сохранили количество циклов до длинного перерыва. Настройка завершена! Теперь можете вызвать команду /startpomodoro чтобы начать работать');
        }
    }
}
