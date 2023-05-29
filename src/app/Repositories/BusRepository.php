<?php

namespace App\Repositories;

use App\Models\Bus;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class BusRepository extends BaseRepository
{
    public function model()
    {
        return Bus::class;
    }

    /**
     * @param  array  $inputs
     *
     * @return Collection
     */
    public function findSeatTypes($inputs)
    {
        return $this->model
            ->select(DB::raw('buses.type, count(trips.id) as trips_number'))
            ->join('trips', 'trips.bus_id', '=', 'buses.id')
            ->join('journeys', 'journeys.id', '=', 'trips.journey_id')
            ->where('journeys.departure_location_id', '=', $inputs['departure_location_id'])
            ->where('journeys.destination_location_id', '=', $inputs['destination_location_id'])
            ->whereTime('trips.departure_time', '>', Carbon::now())
            ->groupBy('buses.type')
            ->get();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }
}
