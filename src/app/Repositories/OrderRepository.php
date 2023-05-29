<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OrderRepository extends BaseRepository
{
    public function model()
    {
        return Order::class;
    }

    /**
     * @param  integer  $status
     *
     * @return Collection
     */
    public function findOrderByStatus($status)
    {
        return $this->model
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * @param  integer  $orderId
     *
     * @return Model|null
     */
    public function findOrderTrips($orderId)
    {
        $order_trips = $this->model
            ->with([
                'orderTrips' => function ($query) {
                    $query->orderBy('ordered_at', 'asc');
                }
            ])
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('id', $orderId)
            ->get();

        return ($order_trips) ? $order_trips->first() : null;
    }

    /**
     * @param  integer  $orderId
     * @param  integer  $status
     *
     * @return boolean
     */
    public function updateStatus($orderId, $status)
    {
        $order = $this->model
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('id', $orderId)
            ->first();

        return ($order) ? $order->update(['status' => $status]) : false;
    }

    /**
     * @param  integer  $orderId
     * @param  Model  $ticket
     *
     * @return boolean
     */
    public function updateTotalPayment($orderId, $ticket)
    {
        $order = $this->model
            ->whereHas('user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('id', $orderId)
            ->first();

        return ($order) ? $order->update(['total_payment' => $order->total_payment - ($ticket->price * $ticket->quantity)]) : false;
    }
}
