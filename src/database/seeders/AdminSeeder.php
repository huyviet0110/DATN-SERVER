<?php

namespace Database\Seeders;

use App\Enums\AdminTypeEnum;
use App\Enums\GenderEnum;
use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $admins_number = 10;

        for ($i = 1; $i <= $admins_number; $i++) {
            $type = $faker->randomElement(AdminTypeEnum::getValues());

            $data = [
                'name'         => $faker->lastName . ' ' . $faker->firstName,
                'email'        => $faker->unique()->freeEmail,
                'password'     => Hash::make('Admin@123098'),
                'avatar'       => $faker->imageUrl,
                'type'         => $type,
                'gender'       => ($type === AdminTypeEnum::SUPER_ADMIN) ? $faker->randomElement(GenderEnum::getValues()) : null,
                'birth_date'   => ($type === AdminTypeEnum::SUPER_ADMIN) ? $faker->date : null,
                'phone_number' => $faker->phoneNumber,
                'address'      => $faker->address,
            ];

            Admin::query()->create($data);
        }
    }
}
