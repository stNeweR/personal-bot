<?php

namespace App\Modules\Pomodoro\Application\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Modules\Pomodoro\Application\DTOs\AddPomodoroSettingsDTO;
use App\Modules\Pomodoro\Application\UseCases\AddPomodoroSettingsForUserUseCase;

final class AddPomodoroSettingsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AddPomodoroSettingsForUserUseCase $useCase
    ) {}

    public function handle(CommandHandlerDTO $data): void
    {
        $this->useCase->execute(new AddPomodoroSettingsDTO($data->telegramId));
    }
}
