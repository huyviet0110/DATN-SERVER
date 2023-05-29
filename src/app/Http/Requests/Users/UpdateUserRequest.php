<?php

namespace App\Http\Requests\Users;

use App\Enums\GenderEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class UpdateUserRequest extends FormRequest
{
    const MINIMUM_AGE = 16;

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
    public function rules()
    {
        return [
            'id'           => 'required|integer|exists:users,id',
            'name'         => 'required|string|regex:/^(?:[\p{Lu}\p{Ll}][a-zà-ỹ]* ?)*[\p{Lu}\p{Ll}][\p{L}]*$/u|between:2,100',
            'avatar'       => 'bail|nullable|image|max:2048',
            'gender'       => ['bail', 'nullable', 'integer', Rule::in(GenderEnum::getValues())],
            'birth_date'   => ['bail', 'nullable', 'date', 'before:' . Carbon::now()->subYears(self::MINIMUM_AGE)],
            'phone_number' => 'bail|nullable|string|between:10,20',
            'address'      => 'bail|nullable|string|between:2,255',
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
