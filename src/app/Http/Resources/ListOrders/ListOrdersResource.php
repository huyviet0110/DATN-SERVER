<?php

namespace App\Http\Resources\ListOrders;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class ListOrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'status'         => $this->status,
            'payment_method' => $this->payment_method,
            'name'           => $this->name,
            'phone'          => $this->phone,
            'email'          => $this->email,
            'total_payment'  => $this->total_payment_formated,
            'created_at'     => Carbon::parse($this->created_at)->format('H:i d/m/Y'),
        ];
    }
}
