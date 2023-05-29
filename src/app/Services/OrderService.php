<?php

namespace App\Services;

use App\Enums\OrderPaymentMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\TicketStatusEnum;
use App\Jobs\SendMailOrderTrip;
use App\Mail\OrderTripMail;
use App\Models\Bus;
use App\Models\OrderTrip;
use App\Models\Trip;
use App\Repositories\OrderRepository;
use App\Repositories\OrderTripRepository;
use App\Repositories\TripRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class OrderService
{
    protected $orderRepository;
    protected $tripRepository;
    protected $orderTripRepository;

    public function __construct(OrderRepository $orderRepository, TripRepository $tripRepository, OrderTripRepository $orderTripRepository)
    {
        $this->orderRepository     = $orderRepository;
        $this->tripRepository      = $tripRepository;
        $this->orderTripRepository = $orderTripRepository;
    }

    /**
     * @param  array  $inputs
     *
     * @return Model
     */
    public function createNewOrder($inputs)
    {
        $order_data = [
            'user_id'        => $inputs['user_id'],
            'name'           => $inputs['name'],
            'phone'          => $inputs['phone'],
            'email'          => $inputs['email'],
        ];

        return $this->orderRepository->create($order_data);
    }

    /**
     * @param  array  $inputs
     * @param  Model  $order
     *
     * @return string|boolean
     */
    public function orderTrips($inputs, $order)
    {
        $total_payment = 0;

        foreach ($inputs['carts'] as $orderInfos) {
            $trip = $this->tripRepository->findByIdAndStation($orderInfos['trip_id'], $orderInfos['pick_up_place'], $orderInfos['drop_off_place']);

            if (is_null($trip)) {
                return Trip::ERR_TRIP_DOESNOT_EXISTS;
            }

            $station_time = $this->tripRepository->findStation($trip, $orderInfos['pick_up_place'], $orderInfos['drop_off_place']);
            $seat_ordered = $this->orderTripRepository->countSeatsRemaining($trip->id, $orderInfos['ordered_at']);

            if ($orderInfos['quantity'] > ($trip->bus->seat_number - $seat_ordered)) {
                return Trip::ERR_EXCEEDED_SEAT_REMAINING;
            }

            $order_detail_data = [
                'order_id'       => $order->id,
                'trip_id'        => $trip->id,
                'pick_up_place'  => $orderInfos['pick_up_place'],
                'drop_off_place' => $orderInfos['drop_off_place'],
                'pick_up_time'   => $station_time->pick_up_time,
                'drop_off_time'  => $station_time->drop_off_time,
                'price'          => $trip->price,
                'quantity'       => $orderInfos['quantity'],
                'ordered_at'     => Carbon::parse($orderInfos['ordered_at'])->addHours(7),
                'status'         => TicketStatusEnum::NOT_CANCELED,
            ];

            $order_detail = $this->orderTripRepository->create($order_detail_data);

            if (is_null($order_detail)) {
                return OrderTrip::ERR_CREATE_NEW_FAILED;
            }
            $order_details[] = $order_detail;

            $total_payment += $trip->price * $orderInfos['quantity'];
        }

        $order->update([
            'total_payment' => $total_payment,
        ]);

        SendMailOrderTrip::dispatch($order, $order_details);

        return true;
    }

    public function all()
    {
        return $this->orderRepository->all();
    }

    public function findById($orderId)
    {
        return $this->orderTripRepository->findById($orderId);
    }

    public function store($inputs)
    {
        return $this->orderRepository->update(Arr::except($inputs, $inputs['id']), $inputs['id']);
    }

    public function destroy($inputs)
    {
        return $this->orderRepository->delete($inputs['id']);
    }
}
