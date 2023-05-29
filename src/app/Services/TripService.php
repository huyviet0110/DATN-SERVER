<?php

namespace App\Services;

use App\Models\Station;
use App\Repositories\AdminRepository;
use App\Repositories\BusRepository;
use App\Repositories\StationRepository;
use App\Repositories\TripRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class TripService
{
    protected $tripRepository;
    protected $adminRepository;
    protected $stationRepository;
    protected $busRepository;

    public function __construct(
        TripRepository $tripRepository,
        AdminRepository $adminRepository,
        StationRepository $stationRepository,
        BusRepository $busRepository,
    ) {
        $this->tripRepository    = $tripRepository;
        $this->adminRepository   = $adminRepository;
        $this->stationRepository = $stationRepository;
        $this->busRepository     = $busRepository;
    }

    /**
     * @param  mixed  $params
     *
     * @return Collection
     */
    public function getTrips($params)
    {
        return $this->tripRepository->findTrips($params);
    }

    /**
     * @param  array  $inputs
     *
     * @return Collection
     */
    public function findByBusStands($inputs)
    {
        return $this->adminRepository->findBusStands($inputs);
    }

    /**
     * @param  array  $inputs
     * @param  string  $place_type
     *
     * @return Collection
     */
    public function findByStationPlaces($inputs, $place_type)
    {
        return $this->stationRepository->findStationPlaces($inputs, $place_type);
    }

    /**
     * @param  array  $inputs
     *
     * @return Collection
     */
    public function findBySeatTypes($inputs)
    {
        return $this->busRepository->findSeatTypes($inputs);
    }

    /**
     * @param  array  $inputs
     *
     * @return Model
     */
    public function getTripDetail($inputs)
    {
        return $this->stationRepository->findTrip($inputs);
    }

    public function all()
    {
        return $this->tripRepository->getAll();
    }

    public function findById($busId)
    {
        return $this->tripRepository->findWithoutRedirect($busId);
    }

    public function store($inputs)
    {
        return $this->tripRepository->update([
            'journey_id'     => $inputs['journey_id'],
            'bus_id'         => $inputs['bus_id'],
            'departure_time' => Carbon::parse($inputs['departure_time']),
            'total_time'     => Carbon::parse($inputs['total_time']),
            'price'          => $inputs['price'],
        ], $inputs['id']);
    }

    public function destroy($inputs)
    {
        return $this->tripRepository->delete($inputs['id']);
    }

    public function create($inputs)
    {
        return $this->tripRepository->create([
            'journey_id'     => $inputs['journey_id'],
            'bus_id'         => $inputs['bus_id'],
            'departure_time' => Carbon::parse($inputs['departure_time']),
            'total_time'     => Carbon::parse($inputs['total_time']),
            'price'          => $inputs['price'],
        ]);
    }

    public function createStation($trip, $inputs)
    {
        return $this->stationRepository->create([
            'trip_id'        => $trip->id,
            'pick_up_place'  => $inputs['pick_up_place'],
            'drop_off_place' => $inputs['drop_off_place'],
            'pick_up_time'   => Carbon::create($inputs['pick_up_time']),
            'drop_off_time'  => Carbon::create($inputs['drop_off_time']),
        ]);
    }

    public function storeStation($inputs)
    {
        return $this->stationRepository->updateStation($inputs);
    }
}
