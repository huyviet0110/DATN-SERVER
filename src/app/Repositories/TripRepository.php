<?php

namespace App\Repositories;

use App\Models\Trip;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class TripRepository extends BaseRepository
{
    public function model()
    {
        return Trip::class;
    }

    /**
     * @param  array  $inputs
     *
     * @return Collection
     */
    public function findTrips($inputs)
    {
        return $this->model
            ->with([
                'bus',
                'bus.admin:id,name',
                'stations',
            ])
            ->withSum([
                'orderTrips' => function ($query) use ($inputs) {
                    $query->whereDate('ordered_at', '=', $inputs['departure_date']);
                }
            ], 'quantity')
            ->filterByLocations($inputs)
            ->filterByDepatureTime()
            ->filterByBusStands($inputs)
            ->filterByStations($inputs, 'pick_up_places', 'pick_up_place')
            ->filterByStations($inputs, 'drop_off_places', 'drop_off_place')
            ->filterBySeatTypes($inputs)
            ->sortTrips($inputs)
            ->get();
    }

    /**
     * @param  integer  $tripId
     * @param  string  $pick_up_place
     * @param  string  $drop_off_place
     *
     * @return Model
     */
    public function findByIdAndStation($tripId, $pick_up_place, $drop_off_place)
    {
        return $this->model
            ->with([
                'journey',
                'bus',
                'bus.admin:id,name',
            ])
            ->where('id', '=', $tripId)
            ->whereHas('stations', function ($query) use ($pick_up_place, $drop_off_place) {
                $query->where('pick_up_place', '=', $pick_up_place);
                $query->where('drop_off_place', '=', $drop_off_place);
            })
            ->first();
    }

    /**
     * @param  Model  $trip
     * @param  string  $pick_up_place
     * @param  string  $drop_off_place
     *
     * @return Model
     */
    public function findStation($trip, $pick_up_place, $drop_off_place)
    {
        return $trip->stations
            ->where('pick_up_place', $pick_up_place)
            ->where('drop_off_place', $drop_off_place)
            ->first();
    }

    public function getAll()
    {
        return $this->model->with('stations')->take(100)->orderBy('id', 'desc')->get();
    }
}
