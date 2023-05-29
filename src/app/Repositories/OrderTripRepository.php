<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Enums\TicketStatusEnum;
use App\Models\OrderTrip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OrderTripRepository extends BaseRepository
{
    public function model()
    {
        return OrderTrip::class;
    }

    /**
     * @param  integer  $trip_id
     * @param  string  $ordered_at
     *
     * @return integer
     */
    public function countSeatsRemaining($trip_id, $ordered_at)
    {
        return $this->model
            ->where('trip_id', '=', $trip_id)
            ->whereDate('ordered_at', '=', Carbon::parse($ordered_at))
            ->groupBy('trip_id')
            ->sum('quantity');
    }

    /**
     * @param  integer  $orderTripId
     *
     * @return Model|null
     */
    public function findOrderDetail($orderTripId)
    {
        $order_trip = $this->model
            ->whereHas('order.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('id', $orderTripId)->get();

        return ($order_trip) ? $order_trip->first() : null;
    }

    /**
     * @param  integer  $orderId
     *
     * @return Model|null
     */
    public function findTicketCanBeCancel($orderId)
    {
        return $this->model
            ->where('id', $orderId)
            ->whereHas('order.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->whereHas('order', function ($query) {
                $query->where('status', OrderStatusEnum::NEW);
            })
            ->where('status', TicketStatusEnum::NOT_CANCELED)
            ->first();
    }

    /**
     * @param  integer  $orderId
     *
     * @return integer
     */
    public function getNumberTicketRemaining($orderId)
    {
        return $this->model
            ->whereHas('order.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->whereHas('order', function ($query) use ($orderId) {
                $query->where('id', $orderId);
            })
            ->where('status', TicketStatusEnum::NOT_CANCELED)
            ->get()
            ->count();
    }

    /**
     * @param  integer  $ticketId
     * @param  integer  $status
     *
     * @return boolean
     */
    public function updateStatus($ticketId, $status)
    {
        $ticket = $this->model
            ->whereHas('order.user', function ($query) {
                $query->where('id', auth()->id());
            })
            ->where('id', $ticketId)
            ->where('status', TicketStatusEnum::NOT_CANCELED)
            ->where('ordered_at', '>', Carbon::now())
            ->first();

        return ($ticket) ? $ticket->update(['status' => $status]) : false;
    }

    /**
     * @param  integer  $orderId
     *
     * @return Collection
     */
    public function findById($orderId)
    {
        return $this->model->with(['order', 'trip'])->where('order_id', $orderId)->get();
    }
}
