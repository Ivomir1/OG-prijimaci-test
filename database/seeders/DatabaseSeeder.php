<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([ //volám naprdění factory
            \Database\Seeders\CountrySeeder::class,
            \Database\Seeders\HolidaySeeder::class,
            \Database\Seeders\TaskSeeder::class,
        ]);
    }
    
}
