<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use Illuminate\Support\Facades\Log;
use App\Modules\User\Domain\Enums\UserStateValue;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\Pomodoro\Application\DTOs\AddPomodoroSettingsDTO;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AddPomodoroSettingsForUserUseCase
{
    public function __construct(
        private readonly UserAdapterInterface $userAdapter,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(AddPomodoroSettingsDTO $data): void
    {
        try {
            $this->userAdapter->updateUserState($data->telegramId, UserStateValue::AWAITING_WORK_DURATION);

            $this->telegramAdapter->sendMessage(
                chatId: $data->telegramId,
                text: 'Пожалуйста теперь введите длительность рабочего времени (одного помодоро)'
            );
        } catch (ModelNotFoundException $e) {
            $this->telegramAdapter->sendMessage(
                chatId: $data->telegramId,
                text: 'Попробуйте позже'
            );

            Log::error('Не удалось сохранить состояние пользователя: ' . $data->telegramId . '. Ошибка - ' . $e->getMessage());
        }
    }
}
