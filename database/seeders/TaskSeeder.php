<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use Carbon\Carbon;
use Faker\Factory as Faker;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 50; $i++) {
            Task::create([
                'title' => $faker->sentence(3),
                'start_datetime' => Carbon::now()->subDays(rand(0, 30))->addHours(rand(0, 23))->addMinutes(rand(0, 59)),
                'duration_minutes' => rand(10, 480), // flákni to mezi 10 minutami a 8 hodinami
                'consider_workdays' => $faker->boolean(),
                'workday_start' => '09:00:00',
                'workday_end' => '17:00:00',
            ]);
        }
    }
}
