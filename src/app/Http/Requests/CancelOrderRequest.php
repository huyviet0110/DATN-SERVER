<?php

namespace App\Http\Requests;

use App\Enums\OrderPaymentMethodEnum;
use App\Enums\OrderStatusEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class CancelOrderRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'order_id'  => 'required|integer|exists:orders,id',
            'ticket_id' => 'required|integer|exists:order_trips,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            responseErrorAPI(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                User::ERR_VALIDATION,
                'Dữ liệu không hợp lệ!'
            )
        );
    }
}
