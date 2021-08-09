<?php

namespace App\Http\Requests\Api\Recipient;

use App\Http\Requests\Api\BaseRequest;
use App\Models\Recipient;
use Illuminate\Validation\Rule;

class UpdateRecipient extends BaseRequest
{
    public function rules()
    {
        return [
            'id' => [
                'required',
                'integer',
                Rule::exists((new Recipient())->getTable(), 'id')
            ],
            'name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
