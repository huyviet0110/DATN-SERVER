<?php

namespace App\Http\Resources\Admins\Orders;

use App\Enums\BusTypeEnum;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ShowOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->order->name,
            'phone' => $this->order->phone,
            'email' => $this->order->email,
            'departure_location' => $this->trip->journey->departureLocation->name,
            'destination_location' => $this->trip->journey->destinationLocation->name,
            'bus_stand' => $this->trip->bus->admin->name,
            'license_plate' => $this->trip->bus->license_plate,
            'seat_number' => $this->trip->bus->seat_number,
            'seat_type' => BusTypeEnum::getKey($this->trip->bus->type),
            'departure_time' => Carbon::parse($this->trip->departure_time)->format('H:i'),
            'total_time' => Carbon::parse($this->trip->total_time)->format('H:i'),
            'pick_up_place' => $this->pick_up_place,
            'pick_up_time' => Carbon::parse($this->pick_up_time)->format('H:i'),
            'drop_off_place' => $this->drop_off_place,
            'drop_off_time' => Carbon::parse($this->drop_off_time)->format('H:i'),
            'price' => $this->price_formated,
            'seat_ordered' => $this->quantity,
            'total_payment' => number_format($this->price * $this->quantity, Trip::NUMBER_DIGITS_AFTER_DECIMALS, '', ','),
        ];
    }
}
