<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'value'     => $this->id,
            'text'      => ($this->parent_id !== 0) ? $this->name . ' - ' . Location::query()->findOrFail($this->parent_id)->name : $this->name,
            'id'        => $this->id,
            'name'      => ($this->parent_id !== 0) ? $this->name . ' - ' . Location::query()->findOrFail($this->parent_id)->name : $this->name,
            'parent_id' => $this->parent_id,
        ];
    }
}
