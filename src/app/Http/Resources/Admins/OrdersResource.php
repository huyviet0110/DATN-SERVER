<?php

namespace App\Http\Resources\Admins;

use App\Enums\OrderPaymentMethodEnum;
use App\Enums\OrderStatusEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class OrdersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->status === OrderStatusEnum::NEW) {
            $status_text = 'Mới';
        } elseif ($this->status === OrderStatusEnum::ALREADY_PAID) {
            $status_text = 'Đã trả tiền';
        } elseif ($this->status === OrderStatusEnum::CANCELED) {
            $status_text = 'Đã hủy';
        } elseif ($this->status === OrderStatusEnum::PAYMENT_FAILED) {
            $status_text = 'Thanh toán thất bại';
        }

        if ($this->payment_method === OrderPaymentMethodEnum::COD) {
            $payment_method_text = 'Ship COD';
        } elseif ($this->payment_method === OrderPaymentMethodEnum::ATM) {
            $payment_method_text = 'ATM';
        } elseif ($this->payment_method === OrderPaymentMethodEnum::INTERNATIONAL_CARD) {
            $payment_method_text = 'Thẻ quốc tế';
        } elseif ($this->payment_method === OrderPaymentMethodEnum::MOMO) {
            $payment_method_text = 'Momo';
        }

        return [
            'id'                      => $this->id,
            'status'                  => $this->status,
            'payment_method'          => $this->payment_method,
            'user_id'                 => $this->user_id ?? null,
            'name'                    => $this->name,
            'phone'                   => $this->phone,
            'email'                   => $this->email,
            'total_payment'           => $this->total_payment,
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
            'created_at_formatted'    => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at_formatted'    => Carbon::parse($this->updated_at)->format('d/m/Y'),
            'total_payment_formatted' => $this->total_payment_formated,
            'status_text'             => $status_text,
            'payment_method_text'     => $payment_method_text,
        ];
    }
}
