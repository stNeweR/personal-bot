<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\GetPomodoroSettingsDTO;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSettingsRepositoryInterface;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

final readonly class GetPomodoroSettingsUseCase
{
    public function __construct(
        private UserAdapterInterface $userAdapter,
        private PomodoroSettingsRepositoryInterface $pomodoroSettingsRepositoryInterface,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(GetPomodoroSettingsDTO $data): void
    {
        try {
            $user = $this->userAdapter->getUserByTelegramId($data->telegramId);

            if (! $user) {
                $this->telegramAdapter->sendMessage(
                    chatId: $data->telegramId,
                    text: 'Пользователь не найден. Пожалуйста, сначала зарегистрируйтесь, используя команду /start'
                );

                return;
            }

            $settings = $this->pomodoroSettingsRepositoryInterface->getByUserId($user->id);

            if (! $settings) {
                $this->telegramAdapter->sendMessage(
                    chatId: $data->telegramId,
                    text: 'У вас пока нет настроек Pomodoro. Используйте команду /addpomosettings для их настройки.'
                );

                return;
            }

            $workDuration = $settings->work_duration;
            $breakDuration = $settings->break_duration;
            $repeatsCount = $settings->repeats_count;
            $longBreakDuration = $settings->long_break_duration ?: 'не установлено';
            $cyclesBeforeLongBreak = $settings->cycles_before_long_break ?: 'не установлено';

            $message = "Ваши текущие настройки Pomodoro:\n\n";
            $message .= "⏱️ Рабочее время: {$workDuration} мин\n";
            $message .= "⏸️ Время перерыва: {$breakDuration} мин\n";
            $message .= "🔄 Количество повторений: {$repeatsCount}\n";
            $message .= "⏸️ Длительный перерыв: {$longBreakDuration} мин\n";
            $message .= "🔄 Циклов перед длинным перерывом: {$cyclesBeforeLongBreak} \n";
            $message .= 'Чтобы запустить таймер вызовите команду /startpomodoro';

            $this->telegramAdapter->sendMessage(
                chatId: $data->telegramId,
                text: $message
            );
        } catch (ModelNotFoundException $e) {
            $this->telegramAdapter->sendMessage(
                chatId: $data->telegramId,
                text: 'Произошла ошибка при получении ваших данных. Попробуйте позже.'
            );

            Log::error('Не удалось получить настройки пользователя: '.$data->telegramId.'. Ошибка - '.$e->getMessage());
        }
    }
}
