<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'trip_id'              => $this->trip_id,
            'departure_location'   => $this->trip->journey->departureLocation->name,
            'destination_location' => $this->trip->journey->destinationLocation->name,
            'license_plate'        => $this->trip->bus->license_plate,
            'pick_up_place'        => $this->pick_up_place,
            'drop_off_place'       => $this->drop_off_place,
            'pick_up_time'         => substr($this->pick_up_time, 0, 5),
            'drop_off_time'        => substr($this->drop_off_time, 0, 5),
            'price'                => $this->trip->price,
            'price_formatted'      => $this->trip->price_formated,
        ];
    }
}
