<?php

namespace Database\Seeders;

use App\Models\Station;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $trips = Trip::query()->get();

        foreach ($trips as $trip) {
            $destination_time = Carbon::create($trip->departure_time)->addHours(Carbon::parse($trip->total_time)->hour)->addMinutes(Carbon::parse($trip->total_time)->minute);

            if (($trip->journey->departureLocation->id === LocationSeeder::ID_OF_HANOI_LOCATION)
                || ($trip->journey->departureLocation->parent_id === LocationSeeder::ID_OF_HANOI_LOCATION)
            ) {
                $pick_up_places = [
                    'Toyota Cầu Diễn'    => Carbon::create($trip->departure_time),
                    'Công viên Hòa Bình' => Carbon::create($trip->departure_time)->addMinutes(5),
                    'Quận Hà Đông'       => Carbon::create($trip->departure_time)->addMinutes(7),
                    'Võ Chí Công'        => Carbon::create($trip->departure_time)->addMinutes(9),
                    'Bưởi'               => Carbon::create($trip->departure_time)->addMinutes(11),
                    'Công viên Nghĩa Đô' => Carbon::create($trip->departure_time)->addMinutes(13),
                ];

                $drop_off_places = [
                    'Bến xe Thái Bình'    => Carbon::create($destination_time)->subMinutes(23),
                    'Bến xe Cổ Lễ'        => Carbon::create($destination_time)->subMinutes(21),
                    'Bến xe Diêm Điền'    => Carbon::create($destination_time)->subMinutes(19),
                    'bến xe Quỹ Nhất'     => Carbon::create($destination_time)->subMinutes(17),
                    'Thành phố Thái Bình' => Carbon::create($destination_time)->subMinutes(15),
                    'Văn phòng Thái Bình' => Carbon::create($destination_time),
                ];

                $this->createTrips($trip->id, $pick_up_places, $drop_off_places);
            } else {
                $pick_up_places = [
                    'Thành phố Thái Bình' => Carbon::create($trip->departure_time),
                    'Văn phòng Thái Bình' => Carbon::create($trip->departure_time)->addMinutes(15),
                    'bến xe Quỹ Nhất'     => Carbon::create($trip->departure_time)->addMinutes(17),
                    'Bến xe Diêm Điền'    => Carbon::create($trip->departure_time)->addMinutes(19),
                    'Bến xe Cổ Lễ'        => Carbon::create($trip->departure_time)->addMinutes(21),
                    'Bến xe Thái Bình'    => Carbon::create($trip->departure_time)->addMinutes(23),
                ];

                $drop_off_places = [
                    'Đường Lê Đức Thọ'    => Carbon::create($destination_time)->subMinutes(13),
                    'Công viên Hòa Bình'  => Carbon::create($destination_time)->subMinutes(11),
                    'Công viên Nghĩa Đô'  => Carbon::create($destination_time)->subMinutes(9),
                    'Khuất Duy Tiến'      => Carbon::create($destination_time)->subMinutes(7),
                    'Công viên Cầu Giấy'  => Carbon::create($destination_time)->subMinutes(5),
                    'Đại học Công nghiệp' => Carbon::create($destination_time),
                ];

                $this->createTrips($trip->id, $pick_up_places, $drop_off_places);
            }
        }
    }

    public function createTrips($trip_id, $pick_up_places, $drop_off_places)
    {
        foreach ($pick_up_places as $pick_up_place => $pick_up_time) {
            foreach ($drop_off_places as $drop_off_place => $drop_off_time) {
                $data = [
                    'trip_id'        => $trip_id,
                    'pick_up_place'  => $pick_up_place,
                    'drop_off_place' => $drop_off_place,
                    'pick_up_time'   => $pick_up_time,
                    'drop_off_time'  => $drop_off_time,
                ];

                Station::query()->create($data);
            }
        }

        return true;
    }
}
