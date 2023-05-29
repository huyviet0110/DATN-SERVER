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

class OrderTripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id'                => 'nullable|integer|exists:users,id',
            'name'                   => 'bail|required|string|between:2,100',
            'phone'                  => 'bail|required|string|between:10,20',
            'email'                  => 'bail|required|email:dns|between:10,100',
            'carts'                  => 'bail|required|array',
            'carts.*.trip_id'        => 'bail|required|integer|exists:trips,id',
            'carts.*.pick_up_place'  => 'bail|required|string|between:2,255|exists:stations,pick_up_place|different:drop_off_place',
            'carts.*.drop_off_place' => 'bail|required|string|between:2,255|exists:stations,drop_off_place|different:pick_up_place',
            'carts.*.quantity'       => 'bail|required|integer|min:1',
            'carts.*.ordered_at'     => 'bail|required|date|after:yesterday',
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
