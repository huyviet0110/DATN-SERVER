<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class DeleteUserRequest extends FormRequest
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
            'id' => 'required|integer|exists:users,id'
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
