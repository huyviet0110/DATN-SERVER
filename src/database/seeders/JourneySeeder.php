<?php

namespace Database\Seeders;

use App\Models\Journey;
use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class JourneySeeder extends Seeder
{
    const ID_NOT_COMPATIBLE_IN_DATABASE = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $location_ids = Location::query()->pluck('id')->toArray();

        foreach ($location_ids as $location_id) {
            $destination_location_number = 3;
            $destination_location_ids    = array_diff(
                array_rand($location_ids, $destination_location_number),
                [self::ID_NOT_COMPATIBLE_IN_DATABASE, $location_id]
            );

            foreach ($destination_location_ids as $destination_location_id) {
                $departure_location   = Location::query()->findOrFail($location_id, 'name')->name;
                $destination_location = Location::query()->findOrFail($destination_location_id, 'name')->name;

                $data = [
                    'departure_location_id'   => $location_id,
                    'destination_location_id' => $destination_location_id,

                    'slug' => Str::slug($departure_location . '-di-' . $destination_location),
                ];

                Journey::query()->create($data);
            }
        }
    }
}
