<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OrderTrip extends Model
{
    use HasFactory;

    const ERR_CREATE_NEW_FAILED = 'E1020';
    const ERR_FIND_ORDER_DETAIL = 'E1023';

    protected $fillable = [
        'order_id',
        'trip_id',
        'pick_up_place',
        'drop_off_place',
        'pick_up_time',
        'drop_off_time',
        'price',
        'quantity',
        'ordered_at',
        'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * @return string
     */
    public function getPickupTimeFormatedAttribute()
    {
        return Carbon::parse($this->pick_up_time)->format('H:i');
    }

    /**
     * @return string
     */
    public function getDropoffTimeFormatedAttribute()
    {
        return Carbon::parse($this->drop_off_time)->format('H:i');
    }

    /**
     * @return string
     */
    public function getPriceFormatedAttribute()
    {
        return number_format($this->price, Trip::NUMBER_DIGITS_AFTER_DECIMALS, '', ',');
    }
}
