<?php

namespace App\Modules\User\Domain\Enums;

enum UserStateValue: string
{
    case AWAITING_WORK_DURATION = 'awaiting_work_duration';
    case AWAITING_BREAK_DURATION = 'awaiting_break_duration';
    case AWAITING_REPEATS_COUNT = 'awaiting_repeats_count';
    case AWAITING_LONG_BREAK_DURATION = 'long_break_duration';
    case AWAITING_CYCLES_BEFORE_LONG_BREAK = 'cycles_before_long_break';

    public static function values(): array
    {
        return [
            self::AWAITING_WORK_DURATION->value,
            self::AWAITING_BREAK_DURATION->value,
            self::AWAITING_REPEATS_COUNT->value,
            self::AWAITING_LONG_BREAK_DURATION->value,
            self::AWAITING_CYCLES_BEFORE_LONG_BREAK->value,
        ];
    }
}
