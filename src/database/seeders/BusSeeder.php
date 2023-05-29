<?php

namespace Database\Seeders;

use App\Enums\AdminTypeEnum;
use App\Enums\BusTypeEnum;
use App\Models\Admin;
use App\Models\Bus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $admin_ids = Admin::query()->where('type', AdminTypeEnum::BUS_OPERATOR)->pluck('id')->toArray();

        foreach ($admin_ids as $admin_id) {
            $buses_number = $faker->numberBetween(5, 10);

            for ($i = 1; $i <= $buses_number; $i++) {
                $data = [
                    'image'         => $faker->imageUrl,
                    'license_plate' => $faker->unique()->regexify("^\d{2}[A-Z] - \d{3}\.\d{2}$"),
                    'seat_number'   => $faker->numberBetween(20, 80),
                    'type'          => $faker->randomElement(BusTypeEnum::getValues()),
                    'content'       => $faker->paragraphs(10, true),
                    'admin_id'      => $admin_id,
                ];

                Bus::query()->create($data);
            }
        }
    }
}
