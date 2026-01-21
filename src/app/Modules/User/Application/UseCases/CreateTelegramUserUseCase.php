<?php

namespace App\Modules\User\Application\UseCases;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\User\Infrastructure\Repository\UserRepository;

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
            'Для того чтобы начать пользоваться ботом, добавьте настройки для помодоро таймера командой - /addpomosettings'
        );
    }
}
