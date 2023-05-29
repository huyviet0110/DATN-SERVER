<?php

namespace App\Http\Resources\Admins;

use App\Enums\GenderEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->gender === GenderEnum::MALE) {
            $gender_text = 'Nam';
        } else {
            $gender_text = 'Nữ';
        }

        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'email'                => $this->email,
            'avatar'               => $this->avatar,
            'gender'               => $this->gender,
            'birth_date'           => $this->birth_date,
            'phone_number'         => $this->phone_number,
            'address'              => $this->address,
            'updated_at'           => $this->updated_at,
            'gender_text'          => $gender_text,
            'updated_at_formatted' => Carbon::parse($this->updated_at)->format('H:i:s d/m/Y'),
        ];
    }
}
