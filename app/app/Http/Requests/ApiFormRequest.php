<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiFormRequest extends FormRequest
{

    protected function failedValidation(Validator $validator) {
        $response = [
            'status' => false,
            'message' => $validator->errors()->first(),
        ];

        throw new HttpResponseException(response()->json($response, 422));
    }
}
