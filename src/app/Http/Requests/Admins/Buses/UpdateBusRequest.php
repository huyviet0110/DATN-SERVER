<?php

namespace App\Http\Requests\Admins\Buses;

use App\Enums\AdminTypeEnum;
use App\Enums\BusTypeEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateBusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'id'            => 'required|integer|exists:buses,id',
            'image'         => 'bail|nullable|image',
            'license_plate' => ['bail', 'required', 'string', Rule::unique('buses', 'license_plate')->ignore($this->input('id'))],
            'seat_number'   => 'bail|required|integer|min:20|max:100',
            'type'          => ['bail', 'required', 'integer', Rule::in(BusTypeEnum::getValues())],
            'admin_id'      => ['bail', 'required', 'integer', Rule::exists('admins', 'id')->where('type', AdminTypeEnum::BUS_OPERATOR)],
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
