<?php

namespace App\Http\Requests\Api\Recipient;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Recipient;
use Illuminate\Validation\Rule;

class DeleteRecipient extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists((new Recipient())->getTable(), 'id')
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
