<?php

namespace App\Http\Resources\ListOrders;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use function PHPUnit\Framework\isEmpty;

class ListOrderTripsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $order = [
            'id'     => $this->id,
            'status' => $this->status,
        ];


        if (!$this->orderTrips->isEmpty()) {
            foreach ($this->orderTrips as $order_trip) {
                $order_trips[] = [
                    'id'             => $order_trip->id,
                    'ordered_at'     => Carbon::parse($order_trip->ordered_at)->format('d/m/Y'),
                    'departure_time' => Carbon::parse($order_trip->trip->departure_time)->format('H:i'),
                    'bus_stand'      => $order_trip->trip->bus->admin->name,
                    'journey'        => $order_trip->trip->journey->departureLocation->name . ' - ' . $order_trip->trip->journey->destinationLocation->name,
                    'license_plate'  => $order_trip->trip->bus->license_plate,
                    'status'         => $order_trip->status,
                ];
            }

            $order_trips_info = ['order_trips' => $order_trips];
        } else {
            $order_trips_info = [];
        }

        return array_merge($order, $order_trips_info);
    }
}
