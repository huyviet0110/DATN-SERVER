<?php

namespace App\Repositories;

use App\Models\Location;

class LocationRepository extends BaseRepository
{
    public function model()
    {
        return Location::class;
    }
}
