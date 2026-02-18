<?php

namespace App\Modules\Pomodoro\Domain\Repository;

use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use Illuminate\Database\Eloquent\Collection;

interface PomodoroSessionsRepositoryInterface
{
    public function create(int $userId): PomodoroSession;

    public function findActiveSession(int $userId): ?PomodoroSession;

    /** @return Collection<int, PomodoroSession> */
    public function getTodaySessions(int $userId): Collection;
}
