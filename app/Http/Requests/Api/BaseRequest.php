<?php

namespace App\Http\Requests\Api;

use App\Models\App;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(App::makeErrorResponse(
            400,
            [],
            $validator->getMessageBag()->first()
        ));
    }
}
