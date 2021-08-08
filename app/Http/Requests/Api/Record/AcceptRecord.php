<?php

namespace App\Http\Requests\Api\Record;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Record;
use Illuminate\Validation\Rule;

class AcceptRecord extends BaseRequest
{
    public function rules()
    {
        return [
            'password' => 'required|string|max:255',
            'identifier' => [
                'required',
                'string',
                'max:255',
                Rule::exists((new Record())->getTable(), 'identifier')
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'identifier' => $this->route('identifier'),
        ]);
    }
}
