<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    const ERR_CREATE_ORDER_FAILED           = 'E1017';
    const ERR_CANCEL_ORDER_FAILED           = 'E1021';
    const ERR_TICKET_NEED_CANCEL_IS_INVALID = 'E1022';
    const ERR_UPDATE_TICKET_STATUS          = 'E1024';
    const ERR_UPDATE_TOTAL_PAYMENT          = 'E1025';
    const ERR_UPDATE_ORDER_STATUS           = 'E1026';

    const ERR_WHEN_CANCEL_TICKET = [
        self::ERR_TICKET_NEED_CANCEL_IS_INVALID => 'Vé không hợp lệ để có thể hủy!',
        self::ERR_UPDATE_TICKET_STATUS          => 'Vé đã quá hạn để có thể hủy!',
        self::ERR_UPDATE_TOTAL_PAYMENT          => 'Cập nhật tổng tiền đơn đặt thất bại!',
        self::ERR_UPDATE_ORDER_STATUS           => 'Cập nhật trạng thái của đơn đặt thất bại khi đơn không còn vé nào!',
    ];

    protected $fillable = [
        'status',
        'payment_method',
        'user_id',
        'name',
        'phone',
        'email',
        'total_payment',
    ];

    public function orderTrips()
    {
        return $this->hasMany(OrderTrip::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    public function getTotalPaymentFormatedAttribute()
    {
        return number_format($this->total_payment, Trip::NUMBER_DIGITS_AFTER_DECIMALS, '', ',');
    }
}
