<?php

namespace App\Modules\Pomodoro\Application\Handlers\State;

use App\Core\Telegram\Application\Handlers\State\StateHandlerDTO;
use App\Core\Telegram\Application\Handlers\State\StateHandlerInterface;
use App\Modules\Pomodoro\Application\DTOs\AddWorkDurationDTO;
use App\Modules\Pomodoro\Application\UseCases\AddWorkDurationUseCase;

final class AwaitingWorkDurationStateHandler implements StateHandlerInterface
{
    public function __construct(
        private readonly AddWorkDurationUseCase $useCase
    ) {}

    public function handle(StateHandlerDTO $data): void
    {
        $this->useCase->execute(AddWorkDurationDTO::from($data->toArray()));
    }
}
