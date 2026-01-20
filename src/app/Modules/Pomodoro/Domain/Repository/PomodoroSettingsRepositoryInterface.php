<?php

namespace App\Modules\Pomodoro\Domain\Repository;

use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSettings;

interface PomodoroSettingsRepositoryInterface
{
    public function create(int $userId, int $workDuration): PomodoroSettings;
}
