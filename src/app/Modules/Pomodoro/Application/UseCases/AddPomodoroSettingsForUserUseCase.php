<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Core\Telegram\Domain\Contracts\TelegramAdapterInterface;
use App\Modules\Pomodoro\Application\DTOs\AddPomodoroSettingsDTO;
use App\Modules\User\Domain\Contracts\UserAdapterInterface;
use App\Modules\User\Domain\Enums\UserStateValue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

final readonly class AddPomodoroSettingsForUserUseCase
{
    public function __construct(
        private UserAdapterInterface $userAdapter,
        private TelegramAdapterInterface $telegramAdapter
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

            Log::error('Не удалось сохранить состояние пользователя: '.$data->telegramId.'. Ошибка - '.$e->getMessage());
        }
    }
}
