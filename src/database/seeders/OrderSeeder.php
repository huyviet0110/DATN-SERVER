<?php

namespace Database\Seeders;

use App\Enums\OrderPaymentMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\TicketStatusEnum;
use App\Models\Order;
use App\Models\OrderTrip;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $trips    = Trip::query()->get();
        $user_ids = User::query()->pluck('id')->toArray();

        $orders_number = 500;

        for ($i = 1; $i <= $orders_number; $i++) {
            $total_payment = 0;

            $data = [
                'status'         => $faker->randomElement(OrderStatusEnum::getValues()),
                'payment_method' => $faker->randomElement(OrderPaymentMethodEnum::getValues()),
                'user_id'        => ($faker->boolean) ? $faker->randomElement($user_ids) : null,
                'name'           => $faker->lastName . ' ' . $faker->firstName,
                'phone'          => $faker->phoneNumber,
                'email'          => $faker->freeEmail,
                'total_payment'  => 0,
            ];

            $order = Order::query()->create($data);

            $trips_number_in_order = $faker->numberBetween(1, 10);

            for ($j = 1; $j <= $trips_number_in_order; $j++) {
                $trip     = $faker->randomElement($trips);
                $station  = $trip->stations->first();
                $quantity = $faker->numberBetween(1, 5);

                $data = [
                    'order_id'       => $order->id,
                    'trip_id'        => $trip->id,
                    'pick_up_place'  => $station->pick_up_place,
                    'drop_off_place' => $station->drop_off_place,
                    'pick_up_time'   => $station->pick_up_time,
                    'drop_off_time'  => $station->drop_off_time,
                    'price'          => $trip->price,
                    'quantity'       => $quantity,
                    'ordered_at'     => Carbon::now()->addDays($faker->numberBetween(0, 3)),
                    'status'         => TicketStatusEnum::NOT_CANCELED,
                ];

                $total_payment += $trip->price * $quantity;

                OrderTrip::query()->create($data);
            }

            $order->update(['total_payment' => $total_payment]);
        }
    }
}
