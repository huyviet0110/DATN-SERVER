<?php

namespace App\Http\Resources\FilterList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PickupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->pick_up_place,
            'trips_number'  => $this->trips_number,
        ];
    }
}
