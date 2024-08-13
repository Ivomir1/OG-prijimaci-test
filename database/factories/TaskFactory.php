<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'start_datetime' => $this->faker->dateTime,
            'duration_minutes' => $this->faker->numberBetween(30, 480),
            'consider_workdays' => $this->faker->boolean,
            'workday_start' => '09:00:00',
            'workday_end' => '17:00:00',
        ];
    }
}
