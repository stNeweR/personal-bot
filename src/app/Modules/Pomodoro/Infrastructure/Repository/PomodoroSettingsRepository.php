<?php

namespace App\Modules\Pomodoro\Infrastructure\Repository;

use App\Modules\Pomodoro\Domain\Repository\PomodoroSettingsRepositoryInterface;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSettings;

final class PomodoroSettingsRepository implements PomodoroSettingsRepositoryInterface
{
    public function create(int $userId, int $workDuration): PomodoroSettings
    {
        return PomodoroSettings::query()
            ->createOrFirst([
                'user_id' => $userId,
                'work_duration' => $workDuration
            ]);
    }
}
