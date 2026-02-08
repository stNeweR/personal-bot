<?php

namespace App\Modules\Pomodoro\Infrastructure\Repository;

use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\Pomodoro\Domain\Repository\PomodoroSessionsRepositoryInterface;
use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

final class PomodoroSessionsRepository implements PomodoroSessionsRepositoryInterface
{
    public function create(int $userId): PomodoroSession
    {
        return PomodoroSession::create([
            'user_id' => $userId,
            'current_status' => PomodoroStatusValue::WORK,
            'start_at' => now(),
            'current_cycle' => 1,
        ]);
    }

    public function findActiveSession(int $userId): ?PomodoroSession
    {
        return PomodoroSession::where('user_id', $userId)
            ->whereNotIn('current_status', [PomodoroStatusValue::FINISHED, PomodoroStatusValue::PAUSED])
            ->first();
    }

    public function getTodaySessions(int $userId): Collection
    {
        return PomodoroSession::where('user_id', $userId)
            ->whereDate('start_at', Carbon::today())
            ->orderBy('start_at', 'asc')
            ->get();

    }
}
