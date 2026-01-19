<?php

namespace App\Modules\Pomodoro\Application\UseCases;

use App\Modules\Pomodoro\Application\DTOs\AddPomodoroSettingsDTO;
use Illuminate\Support\Facades\Log;

class AddPomodoroSettingsForUserUseCase
{
    public function execute(AddPomodoroSettingsDTO $data): void
    {
        Log::debug($data->toArray());
    }
}
