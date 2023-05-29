<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $users_number = 10;

        for ($i = 1; $i <= $users_number; $i++) {
            $data = [
                'name'         => $faker->lastName . ' ' . $faker->firstName,
                'email'        => $faker->unique()->freeEmail,
                'password'     => Hash::make('User@123098'),
                'avatar'       => $faker->imageUrl,
                'gender'       => $faker->randomElement(GenderEnum::getValues()),
                'birth_date'   => Carbon::create($faker->dateTimeBetween('-100 years', '-16 years')),
                'phone_number' => $faker->phoneNumber,
                'address'      => $faker->address,
            ];

            $user = User::query()->create($data);

            $user->markEmailAsVerified();
        }
    }
}
