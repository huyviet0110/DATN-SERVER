<?php

namespace App\Http\Requests;

use App\Enums\AdminTypeEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class SearchTripRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'departure_location_id'   => 'required|integer|exists:journeys,departure_location_id',
            'destination_location_id' => 'bail|required|integer|exists:journeys,destination_location_id',
            'departure_date'          => ['bail', 'required', 'date', 'after:yesterday', 'before:' . Carbon::now()->addMonth()],
            'bus_stands'              => 'bail|nullable|array',
            'bus_stands.*'            => ['integer', Rule::exists('admins', 'id')->where('type', AdminTypeEnum::BUS_OPERATOR)],
            'pick_up_places'          => 'bail|nullable|array',
            'pick_up_places.*'        => 'string|exists:stations,pick_up_place',
            'drop_off_places'         => 'bail|nullable|array',
            'drop_off_places.*'       => 'string|exists:stations,drop_off_place',
            'seat_types'              => 'bail|nullable|array',
            'seat_types.*'            => 'integer|exists:buses,type',
            'sort_field'              => ['bail', 'required_unless:sort_type,null', 'string', Rule::in(['departure_time', 'price'])],
            'sort_type'               => ['bail', 'required_unless:sort_field,null', 'string', Rule::in(['asc', 'desc'])],
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
