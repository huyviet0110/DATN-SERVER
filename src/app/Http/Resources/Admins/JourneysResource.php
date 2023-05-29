<?php

namespace App\Http\Resources\Admins;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class JourneysResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value'                   => $this->id,
            'text'                    => $this->departureLocation->name . ' - ' . $this->destinationLocation->name,
            'id'                      => $this->id,
            'departure_location_id'   => $this->departure_location_id,
            'destination_location_id' => $this->destination_location_id,
            'slug'                    => $this->slug,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
            'departure_text'          => $this->departureLocation->name,
            'destination_text'        => $this->destinationLocation->name,
            'created_at_formatted'    => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at_formatted'    => Carbon::parse($this->updated_at)->format('d/m/Y'),
        ];
    }
}
