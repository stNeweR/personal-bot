<?php

namespace App\Modules\Pomodoro\Application\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\Pomodoro\Application\Jobs\ProcessPomodoroStageJob;
use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use App\Modules\User\Infrastructure\Models\User;

final class StartPomodoroHandler implements CommandHandlerInterface
{
    public function handle(CommandHandlerDTO $data): void
    {
        $user = User::where('telegram_id', $data->telegramId)->first();

        if (! $user) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'Сначала авторизуйтесь в боте командой /start'
            ));
            return;
        }

        $activeSession = PomodoroSession::where('user_id', $user->id)
            ->whereNotIn('current_status', [PomodoroStatusValue::FINISHED, PomodoroStatusValue::PAUSED])
            ->first();

        if ($activeSession) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'У вас уже есть активная сессия'
            ));

            return;
        }

        $session = PomodoroSession::create([
            'user_id' => $user->id,
            'current_status' => PomodoroStatusValue::WORK, 
            'start_at' => now(),
            'current_cycle' => 1,
        ]);

        ProcessPomodoroStageJob::dispatch($session, $user, 1, PomodoroStatusValue::WORK);
    }
}
