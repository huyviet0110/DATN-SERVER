<?php

namespace App\Http\Resources\ListOrders;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'departure_location'   => $this->trip->journey->departureLocation->name,
            'destination_location' => $this->trip->journey->destinationLocation->name,
            'bus_stand'            => $this->trip->bus->admin->name,
            'bus_license_plate'    => $this->trip->bus->license_plate,
            'seat_number'          => $this->trip->bus->seat_number,
            'seat_type'            => $this->trip->bus->type,
            'bus_image'            => env('APP_BASE_BE_URL') . '/storage/images/buses/' . $this->trip->bus->image,
            'departure_time'       => $this->trip->departure_time_formated,
            'total_time'           => $this->trip->total_time_formated,
            'pick_up_place'        => $this->pick_up_place,
            'pick_up_time'         => $this->pickup_time_formated,
            'drop_off_place'       => $this->drop_off_place,
            'drop_off_time'        => $this->dropoff_time_formated,
            'status'               => $this->order->status,
            'price'                => $this->price_formated,
            'seat_ordered'         => $this->quantity,
            'total_price'          => number_format($this->price * $this->quantity, Trip::NUMBER_DIGITS_AFTER_DECIMALS, '', ','),
        ];
    }
}
