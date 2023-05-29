<?php

namespace App\Repositories;

use App\Enums\AdminTypeEnum;
use App\Models\Admin;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

class AdminRepository extends BaseRepository
{
    public function model()
    {
        return Admin::class;
    }

    /**
     * @param  array  $inputs
     *
     * @return Collection
     */
    public function findBusStands($inputs)
    {
        return $this->model
            ->withCount([
                'busTrips' => function (Builder $query) use ($inputs) {
                    $query->whereTime('departure_time', '>', Carbon::now());
                    $query->whereHas('journey', function ($query) use ($inputs) {
                        $query->where('departure_location_id', $inputs['departure_location_id']);
                        $query->where('destination_location_id', $inputs['destination_location_id']);
                    });
                }
            ])
            ->filterByLocations($inputs['departure_location_id'] ?? 0, $inputs['destination_location_id'] ?? 0)
            ->filterByDepatureTime()
            ->filterByStations($inputs['pick_up_places'] ?? [], 'pick_up_place')
            ->filterByStations($inputs['drop_off_places'] ?? [], 'drop_off_place')
            ->filterBySeatTypes($inputs['seat_types'] ?? [])
            ->get();
    }

    public function findBusOperators()
    {
        return $this->model
            ->where('type', AdminTypeEnum::BUS_OPERATOR)
            ->get();
    }
}
