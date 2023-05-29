<?php

namespace App\Http\Resources;

use App\Enums\BusTypeEnum;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $departure_time   = Carbon::parse($this->departure_time);
        $total_time       = formatTimeToCarbon($this->total_time);
        $destination_time = Carbon::parse($this->departure_time)->addDays($total_time->day)->addHours($total_time->hour)->addMinutes($total_time->minute);
        $seat_ordered     = 0;
        foreach ($this->orderTrips as $order_trip) {
            if (Carbon::parse($request->input('departure_date'))->format('d-m-Y') === (Carbon::parse($order_trip->ordered_at)->format('d-m-Y'))) {
                $seat_ordered += $order_trip->quantity;
            }
        }

        $trips_info = [
            'id'               => $this->id,
            'journey_id'       => $this->journey_id,
            'bus_id'           => $this->bus_id,
            'departure_time'   => $departure_time->format('H:i'),
            'total_time'       => substr($this->total_time, 0, 5),
            'destination_time' => $destination_time->format('H:i'),
            'price'            => $this->price_formated,
            'seat_available'   => $this->bus->seat_number - $seat_ordered
        ];

        $buses_info = [
            'bus' => [
                'id'            => $this->bus->id,
                'image'         => env("APP_BASE_BE_URL") . '/storage/images/buses/' . $this->bus->image,
                'license_plate' => $this->bus->license_plate,
                'seat_number'   => $this->bus->seat_number,
                'type'          => $this->bus->type,
                'bus_stand_id'  => $this->bus->admin->id,

                'bus_stand' => [
                    'id'   => $this->bus->admin->id,
                    'name' => $this->bus->admin->name,
                ],
            ],
        ];

        foreach ($this->stations as $station) {
            $stations[] = [
                'id'             => $station->id,
                'trip_id'        => $station->trip_id,
                'pick_up_place'  => $station->pick_up_place,
                'drop_off_place' => $station->drop_off_place,
                'pick_up_time'   => substr($station->pick_up_time, 0, 5),
                'drop_off_time'  => substr($station->drop_off_time, 0, 5),
            ];
        }

        $stations_info = [
            'stations' => $stations,
        ];

        return array_merge($trips_info, $buses_info, $stations_info);
    }
}
