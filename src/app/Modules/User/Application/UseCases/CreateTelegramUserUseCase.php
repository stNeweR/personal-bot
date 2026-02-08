<?php

namespace App\Modules\User\Application\UseCases;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Infrastructure\Adapters\TelegramAdapter;
use App\Modules\User\Domain\Repository\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

final readonly class CreateTelegramUserUseCase
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private TelegramAdapter $telegramAdapter
    ) {}

    public function execute(CommandHandlerDTO $data): void
    {
        try {
            $this->userRepository->getByTelegramId($data->telegramId);

            $this->telegramAdapter->sendMessage(
                $data->telegramId,
                'Вы уже пользовались ботом. Для того чтобы посмотреть свои настройки таймера, выполните команду /getpomosettings.'
            );
        } catch (ModelNotFoundException $e) {
            $this->userRepository->createUser($data->telegramId);

            $this->telegramAdapter->sendMessage(
                $data->telegramId,
                'Для того чтобы начать пользоваться ботом, добавьте настройки для помодоро таймера командой - /addpomosettings'
            );
        }
    }
}
