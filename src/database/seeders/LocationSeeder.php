<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LocationSeeder extends Seeder
{
    const ID_OF_HANOI_LOCATION = 1;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $provinces = [
            'Hà Nội' => [
                'Hoàng Mai', 'Long Biên', 'Thanh Xuân', 'Bắc Từ Liêm', 'Ba Đình', 'Cầu Giấy', 'Đống Đa', 'Hai Bà Trưng',
                'Hoàn Kiếm', 'Hà Đông', 'Tây Hồ', 'Nam Từ Liêm', 'Đan Phượng', 'Gia Lâm', 'Đông Anh', 'Chương Mỹ',
            ],

            'Thái Bình' => [
                'Thái Bình', 'Đông Hưng', 'Hưng Hà', 'Kiến Xương', 'Quỳnh Phụ', 'Thái Thụy', 'Tiền Hải', 'Vũ Thư',
            ],
        ];

        foreach ($provinces as $province => $districts) {
            $data = [
                'name' => $province,
            ];

            $location = Location::query()->create($data);

            foreach ($districts as $district) {
                $data = [
                    'name'      => $district,
                    'parent_id' => $location->id,
                ];

                Location::query()->create($data);
            }
        }
    }
}
