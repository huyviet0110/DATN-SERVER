<?php

namespace App\Models;

use App\Enums\BusTypeEnum;
use BenSampo\Enum\Exceptions\InvalidEnumMemberException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Bus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'image',
        'license_plate',
        'seat_number',
        'type',
        'content',
        'admin_id',
    ];

    protected $hidden = [
        'content',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * @param  Builder  $query
     * @param  integer  $departure_location_id
     * @param  integer  $destination_location_id
     *
     * @return Builder
     */
    public function scopeFilterByLocations($query, $departure_location_id, $destination_location_id)
    {
        return (!empty($departure_location_id) && !empty($destination_location_id))
            ? $query->whereHas('trips.journey', function ($query) use ($departure_location_id, $destination_location_id) {
                $query->where('departure_location_id', $departure_location_id);
                $query->where('destination_location_id', $destination_location_id);
            })
            : null;
    }

    /**
     * @param  Builder  $query
     * @param  string  $departure_date
     *
     * @return Builder
     */
    public function scopeFilterByOrderDate($query, $departure_date)
    {
        return (!empty($departure_date))
            ? $query->whereHas('trips.orderTrips', function ($query) use ($departure_date) {
                $query->whereDate('ordered_at', '=', $departure_date);
            })
            : null;
    }

    /**
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopeFilterByDepatureTime($query)
    {
        return $query->whereHas('trips', function ($query) {
            $query->whereTime('departure_time', '>=', Carbon::now());
        });
    }

    /**
     * @param  Builder  $query
     * @param  array  $bus_stands
     *
     * @return Builder
     */
    public function scopeFilterByBusStands($query, $bus_stands)
    {
        return (!empty($bus_stands))
            ? $query->whereHas('admin', function ($query) use ($bus_stands) {
                $query->whereIn('id', $bus_stands);
            })
            : null;
    }

    /**
     * @param  Builder  $query
     * @param  array  $stations
     * @param  string  $station_type
     *
     * @return Builder
     */
    public function scopeFilterByStations($query, $stations, $station_type)
    {
        return (!empty($stations) && !empty($station_type))
            ? $query->whereHas('trips.stations', function ($query) use ($stations, $station_type) {
                $query->whereIn($station_type, $stations);
            })
            : null;
    }

    /**
     * @param  Builder  $query
     * @param  array  $seat_types
     *
     * @return Builder
     */
    public function scopeFilterBySeatTypes($query, $seat_types)
    {
        return (!empty($seat_types)) ? $query->whereIn('type', $seat_types) : null;
    }

    /**
     * @return string
     */
    public function getSeatTypeNameAttribute()
    {
        return ucfirst(strtolower(BusTypeEnum::getKey($this->type)));
    }
}
