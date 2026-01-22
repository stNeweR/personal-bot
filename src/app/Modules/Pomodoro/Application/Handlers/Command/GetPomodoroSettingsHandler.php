<?php

namespace App\Modules\Pomodoro\Application\Handlers\Command;

use App\Core\Telegram\Application\Handlers\Command\CommandHandlerDTO;
use App\Core\Telegram\Application\Handlers\Command\CommandHandlerInterface;
use App\Modules\Pomodoro\Application\DTOs\GetPomodoroSettingsDTO;
use App\Modules\Pomodoro\Application\UseCases\GetPomodoroSettingsUseCase;

final class GetPomodoroSettingsHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly GetPomodoroSettingsUseCase $useCase
    ) {}

    public function handle(CommandHandlerDTO $data): void
    {
        if ($data->telegramId === null) {
            return;
        }
        
        $this->useCase->execute(new GetPomodoroSettingsDTO(telegramId: $data->telegramId));
    }
}