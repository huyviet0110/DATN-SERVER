<?php

namespace App\Services;

use App\Repositories\LocationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class LocationService
{
    protected $locationRepository;

    public function __construct(LocationRepository $locationRepository)
    {
        $this->locationRepository = $locationRepository;
    }

    /**
     * @return Collection
     */
    public function getAllLocations()
    {
        return $this->locationRepository->all();
    }
}
