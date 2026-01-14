<?php

namespace App\Modules\Pomodoro\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Modules\User\Infrastructure\Repository\UserRepository;

final class AddPomodoroSettingsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {}

    public function handle(CommandHandlerDTO $data): void
    {
        $user = $this->userRepository->getByTelegramId($data->telegramId);

        dd($user);
    }
}
