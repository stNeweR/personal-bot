<?php

namespace App\Modules\Pomodoro\Infrastructure\Models;

use App\Modules\Pomodoro\Domain\Enums\PomodoroStatusValue;
use App\Modules\User\Infrastructure\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PomodoroSession extends Model
{
    protected $table = 'pomodoro_session';

    protected $fillable = [
        'user_id',
        'current_status',
        'start_at',
        'current_cycle',
    ];

    protected $casts = [
        'current_status' => PomodoroStatusValue::class,
        'start_at' => 'datetime',
        'current_cycle' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
