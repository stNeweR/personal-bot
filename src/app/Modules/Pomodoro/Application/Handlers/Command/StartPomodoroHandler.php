<?php

namespace App\Modules\Pomodoro\Application\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Modules\Pomodoro\Application\Jobs\ProcessPomodoroStageJob;
use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use App\Modules\User\Infrastructure\Models\User;

final class StartPomodoroHandler implements CommandHandlerInterface
{
    public function handle(CommandHandlerDTO $data): void
    {
        // Получаем пользователя по telegram_id
        $user = User::where('telegram_id', $data->telegramId)->first();

        if (! $user) {
            // Отправляем сообщение, что сначала нужно зарегистрироваться
            return;
        }

        // Проверяем, нет ли уже активной сессии
        $activeSession = PomodoroSession::where('user_id', $user->id)
            ->whereNotIn('current_status', [PomodoroStatusValue::FINISHED, PomodoroStatusValue::PAUSED])
            ->first();

        if ($activeSession) {
            // Отправляем сообщение, что уже есть активная сессия
            return;
        }

        // Создаем новую сессию
        $session = PomodoroSession::create([
            'user_id' => $user->id,
            'current_status' => PomodoroStatusValue::WORK, // Начинаем с работы
            'start_at' => now(),
            'current_cycle' => 1,
        ]);

        // Запускаем Job для управления Pomodoro сессией - начинаем с рабочего периода
        ProcessPomodoroStageJob::dispatch($session, $user, 1, PomodoroStatusValue::WORK);
    }
}
