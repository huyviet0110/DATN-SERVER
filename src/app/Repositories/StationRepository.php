<?php

namespace App\Repositories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StationRepository extends BaseRepository
{
    public function model()
    {
        return Station::class;
    }

    /**
     * @param  array  $inputs
     * @param  string  $place_type
     *
     * @return Collection
     */
    public function findStationPlaces($inputs, $place_type)
    {
        return $this->model
            ->select(DB::raw("$place_type, count(distinct trip_id) as trips_number"))
            ->filterByLocations($inputs['departure_location_id'] ?? 0, $inputs['destination_location_id'] ?? 0)
            ->filterByDepatureTime()
            ->filterByBusStands($inputs['bus_stands'] ?? [])
            ->filterByStations($inputs['pick_up_places'] ?? [], 'pick_up_place')
            ->filterByStations($inputs['drop_off_places'] ?? [], 'drop_off_place')
            ->filterBySeatTypes($inputs['seat_types'] ?? [])
            ->groupBy($place_type)
            ->get();
    }

    /**
     * @param  array  $inputs
     *
     * @return Model
     */
    public function findTrip($inputs)
    {
        return $this->model
            ->whereHas('trip', function ($query) use ($inputs) {
                $query->where('id', $inputs['trip_id']);
            })
            ->where('pick_up_place', $inputs['pick_up_place'])
            ->where('drop_off_place', $inputs['drop_off_place'])
            ->first();
    }

    public function updateStation($inputs)
    {
        $station = Station::query()
            ->where('trip_id', $inputs['id'])
            ->first();

        return ($station)
            ? $station->update([
                'pick_up_place'  => $inputs['pick_up_place'],
                'drop_off_place' => $inputs['drop_off_place'],
                'pick_up_time'   => $inputs['pick_up_time'],
                'drop_off_time'  => $inputs['drop_off_time'],
            ])
            : false;
    }
}
