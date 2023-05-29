<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopJourneyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'departure_location_id'   => $this->departure_location_id,
            'destination_location_id' => $this->destination_location_id,
            'total_quantity'          => $this->order_trips_sum_quantity,
            'price'                   => $this->price_formated,

            'departure_location' => [
                'id'   => $this->departureLocation->id,
                'name' => $this->departureLocation->name,
            ],

            'destination_location' => [
                'id'   => $this->destinationLocation->id,
                'name' => $this->destinationLocation->name,
            ],
        ];
    }
}
