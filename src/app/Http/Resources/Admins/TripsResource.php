<?php

namespace App\Http\Resources\Admins;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TripsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                        => $this->id,
            'journey_id'                => $this->journey_id,
            'bus_id'                    => $this->bus_id,
            'departure_time'            => Carbon::parse($this->departure_time)->format('H:i'),
            'total_time'                => $this->total_time,
            'price'                     => $this->price,
            'created_at'                => $this->created_at,
            'updated_at'                => $this->updated_at,
            'created_at_formatted'      => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at_formatted'      => Carbon::parse($this->updated_at)->format('d/m/Y'),
            'price_formatted'           => $this->price_formated,
            'departure_time_formatted'  => Carbon::parse($this->departure_time)->format('H:i'),
            'total_time_formatted'      => Carbon::parse($this->total_time)->format('H:i'),
            'departure_location_name'   => $this->journey->departureLocation->name,
            'destination_location_name' => $this->journey->destinationLocation->name,
            'license_plate'             => $this->bus->license_plate,
            'bus_stand_name'            => $this->bus->admin->name,
            'bus_stand'                 => $this->bus->license_plate . ' - ' . $this->bus->admin->name,
            'pick_up_place'             => $this->stations->first()->pick_up_place ?? '',
            'drop_off_place'            => $this->stations->first()->drop_off_place ?? '',
            'pick_up_time'              => Carbon::parse($this->pick_up_time)->format('H:i'),
            'drop_off_time'             => Carbon::parse($this->drop_off_time)->format('H:i'),
        ];
    }
}
