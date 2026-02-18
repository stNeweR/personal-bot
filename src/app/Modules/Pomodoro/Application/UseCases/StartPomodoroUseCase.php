<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Domain\Contracts\TelegramApiClientInterface;
use App\Core\Telegram\Infrastructure\Services\Telegram\DTOs\SendMessageDTO;
use App\Modules\Pomodoro\Application\DTOs\StartPomodoroDTO;
use App\Modules\Pomodoro\Application\Jobs\ProcessPomodoroStageJob;
use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSessionsRepositoryInterface;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class StartPomodoroUseCase
{
    public function __construct(
        private PomodoroSessionsRepositoryInterface $pomodoroSessionsRepository,
        private UserAdapterInterface $userAdapter,
        private TelegramApiClientInterface $telegramApiClient,
    ) {}

    public function execute(StartPomodoroDTO $data): void
    {
        try {
            $user = $this->userAdapter->getUserByTelegramId($data->telegramId);
        } catch (ModelNotFoundException) {
            $this->telegramApiClient->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'Сначала авторизуйтесь в боте командой /start'
            ));

            return;
        }

        $activeSession = $this->pomodoroSessionsRepository->findActiveSession($user->id);

        if ($activeSession) {
            $this->telegramApiClient->sendMessage(new SendMessageDTO(
                $data->telegramId,
                'У вас уже есть активная сессия'
            ));

            return;
        }

        $session = $this->pomodoroSessionsRepository->create($user->id);

        ProcessPomodoroStageJob::dispatch($session, $user, 1, PomodoroStatusValue::WORK);
    }
}
