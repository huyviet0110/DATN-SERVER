<?php

namespace App\Http\Resources\Admins\Buses;

use App\Enums\BusTypeEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BusesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->type === BusTypeEnum::SEAT) {
            $type_text = 'Xe ghế ngồi';
        } elseif ($this->type === BusTypeEnum::BUNK) {
            $type_text = 'Xe giường nằm';
        } else {
            $type_text = 'Limousine';
        }

        return [
            'value'                => $this->id,
            'text'                 => $this->license_plate . ' - ' . $this->admin->name,
            'id'                   => $this->id,
            'image'                => $this->image,
            'license_plate'        => $this->license_plate,
            'seat_number'          => $this->seat_number,
            'type'                 => $this->type,
            'admin_id'             => $this->admin_id,
            'created_at'           => $this->created_at,
            'updated_at'           => $this->updated_at,
            'type_text'            => $type_text,
            'bus_stand'            => $this->admin->name,
            'created_at_formatted' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at_formatted' => Carbon::parse($this->updated_at)->format('d/m/Y'),
        ];
    }
}
