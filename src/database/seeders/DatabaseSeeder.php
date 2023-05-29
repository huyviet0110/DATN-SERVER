<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $this->call([
            AdminSeeder::class,
            BusSeeder::class,
            LocationSeeder::class,
            JourneySeeder::class,
            TripSeeder::class,
            StationSeeder::class,
            UserSeeder::class,
            OrderSeeder::class,
        ]);
    }
}
