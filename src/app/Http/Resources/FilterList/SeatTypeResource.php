<?php

namespace App\Http\Resources\FilterList;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SeatTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type'         => $this->type,
            'trips_number' => (int) $this->trips_number,
        ];
    }
}
