<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Core\Telegram\Infrastructure\Services\Telegram\TelegramApiClient;
use App\Modules\Pomodoro\Application\DTOs\StartPomodoroDTO;
use App\Modules\Pomodoro\Application\Jobs\ProcessPomodoroStageJob;
use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSessionsRepositoryInterface;
use App\Modules\User\Infrastructure\Models\User;

final readonly class StartPomodoroUseCase
{
    public function __construct(
        private PomodoroSessionsRepositoryInterface $pomodoroSessionsRepository
    ) {}

    public function execute(StartPomodoroDTO $data): void
    {
        $user = User::where('telegram_id', $data->telegramId)->first();

        if (! $user) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'Сначала авторизуйтесь в боте командой /start'
            ));

            return;
        }

        $activeSession = $this->pomodoroSessionsRepository->findActiveSession($user->id);

        if ($activeSession) {
            (new TelegramApiClient)->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'У вас уже есть активная сессия'
            ));

            return;
        }

        $session = $this->pomodoroSessionsRepository->create($user->id);

        ProcessPomodoroStageJob::dispatch($session, $user, 1, PomodoroStatusValue::WORK);

    }
}
