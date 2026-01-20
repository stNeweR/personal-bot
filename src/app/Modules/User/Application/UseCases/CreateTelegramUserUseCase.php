<?php

namespace App\Modules\User\Application\UseCases;

use Illuminate\Support\Facades\Log;
use App\Modules\User\Infrastructure\Repository\UserRepository;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;

final readonly class CreateTelegramUserUseCase
{
    public function __construct(
        private UserRepository $userRepository,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(CommandHandlerDTO $data): void
    {
        $this->userRepository->createUser($data->telegramId);

        $this->telegramAdapter->sendMessage(
            $data->telegramId,
            $data->message
        );
    }
}
