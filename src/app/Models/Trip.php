<?php

namespace App\Models;

use App\Enums\AdminTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Trip extends Model
{
    use HasFactory;

    const NUMBER_OF_TOP_JOURNEYS       = 8;
    const NUMBER_OF_DAYS_NEEDED        = 30;
    const NUMBER_DIGITS_AFTER_DECIMALS = 0;

    const ERR_SEARCH_TRIPS            = 'E1012';
    const ERR_FIND_BUS_STANDS         = 'E1013';
    const ERR_FIND_PICKUP_PLACES      = 'E1014';
    const ERR_FIND_DROPOFF_PLACES     = 'E1015';
    const ERR_FIND_SEAT_TYPES         = 'E1016';
    const ERR_TRIP_DOESNOT_EXISTS     = 'E1018';
    const ERR_EXCEEDED_SEAT_REMAINING = 'E1019';

    protected $fillable = [
        'journey_id',
        'bus_id',
        'departure_time',
        'total_time',
        'note',
        'price',
    ];

    protected $hidden = [
        'note',
    ];

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function orderTrips()
    {
        return $this->hasMany(OrderTrip::class);
    }

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function stations()
    {
        return $this->hasMany(Station::class);
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     *
     * @return Builder
     */
    public function scopeFilterByLocations($query, $inputs)
    {
        return $query->whereHas('journey', function ($query) use ($inputs) {
            $query->where('departure_location_id', $inputs['departure_location_id']);
            $query->where('destination_location_id', $inputs['destination_location_id']);
        });
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     *
     * @return Builder
     */
    public function scopeFilterByOrderDate($query, $inputs)
    {
        return $query->whereHas('orderTrips', function ($query) use ($inputs) {
            $query->whereDate('ordered_at', '=', $inputs['departure_date']);
        });
    }

    /**
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeFilterByDepatureTime($query)
    {
        return $query->whereTime('departure_time', '>=', Carbon::now());
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     *
     * @return Builder
     */
    public function scopeFilterByBusStands($query, $inputs)
    {
        return $query->when(!empty($inputs['bus_stands']), function ($query) use ($inputs) {
            $query->whereHas('bus.admin', function ($query) use ($inputs) {
                $query->whereIn('id', $inputs['bus_stands']);
            });
        });
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     * @param  string  $key
     * @param  string  $place_type
     *
     * @return Builder
     */
    public function scopeFilterByStations($query, $inputs, $key, $place_type)
    {
        return $query->when(!empty($inputs[$key]), function ($query) use ($inputs, $key, $place_type) {
            $query->whereHas('stations', function ($query) use ($inputs, $key, $place_type) {
                $query->whereIn($place_type, $inputs[$key]);
            });
        });
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     *
     * @return Builder
     */
    public function scopeFilterBySeatTypes($query, $inputs)
    {
        return $query->when(!empty($inputs['seat_types']), function ($query) use ($inputs) {
            $query->whereHas('bus', function ($query) use ($inputs) {
                $query->whereIn('type', $inputs['seat_types']);
            });
        });
    }

    /**
     * @param  Builder  $query
     * @param  array  $inputs
     *
     * @return Builder
     */
    public function scopeSortTrips($query, $inputs)
    {
        return $query->when(isset($inputs['sort_field'], $inputs['sort_type']), function ($query) use ($inputs) {
            return ($inputs['sort_field'] === 'departure_time')
                ? $query->orderByRaw('TIME(departure_time)' . $inputs['sort_type'])
                : $query->orderBy($inputs['sort_field'], $inputs['sort_type']);
        });
    }

    /**
     * @return string
     */
    public function getDepartureTimeFormatedAttribute()
    {
        return Carbon::parse($this->departure_time)->format('H:i');
    }

    /**
     * @return string
     */
    public function getTotalTimeFormatedAttribute()
    {
        return Carbon::parse($this->total_time)->format('H:i');
    }

    public function getPriceFormatedAttribute()
    {
        return number_format($this->price, self::NUMBER_DIGITS_AFTER_DECIMALS, '', ',');
    }
}
