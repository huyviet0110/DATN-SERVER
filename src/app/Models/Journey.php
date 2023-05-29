<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journey extends Model
{
    use HasFactory;

    const NUMBER_OF_TOP_JOURNEYS = 8;
    const NUMBER_OF_DAYS_NEEDED  = 30;

    const ERR_GET_TOP_JOURNEYS = 'E1010';
    const ERR_FIND_JOURNEY     = 'E1011';

    protected $fillable = [
        'departure_location_id',
        'destination_location_id',
        'slug',
    ];

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    public function departureLocation()
    {
        return $this->belongsTo(Location::class, 'departure_location_id', 'id');
    }

    public function destinationLocation()
    {
        return $this->belongsTo(Location::class, 'destination_location_id', 'id');
    }

    public function orderTrips()
    {
        return $this->hasManyThrough(OrderTrip::class, Trip::class);
    }

    public function tripStations()
    {
        return $this->hasManyThrough(Station::class, Trip::class);
    }

    public function getPriceFormatedAttribute()
    {
        return number_format($this->trips_avg_price, Trip::NUMBER_DIGITS_AFTER_DECIMALS, '', ',');
    }
}
