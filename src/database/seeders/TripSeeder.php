<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\Journey;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TripSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $journey_ids = Journey::query()->pluck('id')->toArray();
        $bus_ids     = Bus::query()->pluck('id')->toArray();

        foreach ($journey_ids as $journey_id) {
            $trips_number = $faker->numberBetween(30, 50);

            for ($i = 1; $i < $trips_number; $i++) {
                $data = [
                    'journey_id'     => $journey_id,
                    'bus_id'         => $faker->randomElement($bus_ids),
                    'departure_time' => Carbon::now()->addHours($faker->numberBetween(0, 24)),
                    'total_time'     => $faker->dateTimeBetween(Carbon::now(), Carbon::now()->addHours(72))->format('H:i:s'),
                    'note'           => $faker->paragraphs(2, true),
                    'price'          => $faker->randomElement([200000, 300000, 400000]),
                ];

                Trip::query()->create($data);
            }
        }
    }
}
