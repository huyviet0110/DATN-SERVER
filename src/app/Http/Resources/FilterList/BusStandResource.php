<?php

namespace App\Http\Resources\FilterList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BusStandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'trips_number' => $this->bus_trips_count,
        ];
    }
}
