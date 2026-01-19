<?php

namespace Database\Factories;

use App\Modules\Pomodoro\Infrastructure\Models\PomodoroSettings;
use Illuminate\Database\Eloquent\Factories\Factory;

class PomodoroSettingsFactory extends Factory
{
    protected $model = PomodoroSettings::class;

    public function definition(): array
    {
        return [
            'user_id' => UserFactory::new(),
            'work_duration' => fake()->numberBetween(1, 100),
            'break_duration' => fake()->numberBetween(1, 100),
            'repeats_count' => fake()->numberBetween(1, 100),
            'long_break_duration' => fake()->numberBetween(1, 100),
            'cycles_before_long_break' => fake()->numberBetween(1, 100),
        ];
    }
}
