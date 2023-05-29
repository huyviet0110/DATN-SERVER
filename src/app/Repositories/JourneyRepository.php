<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Models\Journey;
use App\Models\OrderTrip;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class JourneyRepository extends BaseRepository
{
    public function model()
    {
        return Journey::class;
    }

    /**
     * @param  integer  $numberOfTopJourneys
     *
     * @return Collection
     */
    public function findTopJourneysInMonth($numberOfTopJourneys)
    {
        return $this->model
            ->with([
                'departureLocation:id,name',
                'destinationLocation:id,name',
            ])
            ->withSum('orderTrips', 'quantity')
            ->withAvg('trips', 'price')
            ->whereHas('orderTrips', function ($query) {
                $query->where('order_trips.created_at', '>=', Carbon::now()->subDays(Journey::NUMBER_OF_DAYS_NEEDED));
            })
            ->whereHas('orderTrips.order', function ($query) {
                $query->where('status', '!=', OrderStatusEnum::CANCELED);
            })
            ->get()
            ->sortByDesc('order_trips_sum_quantity')
            ->take($numberOfTopJourneys);
    }

    /**
     * @param  integer  $numberOfJourneysNeeded
     *
     * @return Collection
     */
    public function findJourneys($numberOfJourneysNeeded)
    {
        return $this->model
            ->with([
                'departureLocation:id,name',
                'destinationLocation:id,name'
            ])
            ->take($numberOfJourneysNeeded)
            ->get();
    }

    /**
     * @param  array  $inputs
     *
     * @return Model
     */
    public function findByLocation($inputs)
    {
        return $this->model
            ->with([
                'departureLocation:id,name',
                'destinationLocation:id,name'
            ])
            ->where('departure_location_id', '=', $inputs['departure_location_id'])
            ->where('destination_location_id', '=', $inputs['destination_location_id'])
            ->first();
    }

    public function findJourneyByLocations($inputs)
    {
        return $this->model
            ->where('departure_location_id', $inputs['departure_location_id'])
            ->where('destination_location_id', $inputs['destination_location_id'])
            ->first();
    }

    public function getAll()
    {
        return $this->model->orderBy('id', 'desc')->get();
    }
}
