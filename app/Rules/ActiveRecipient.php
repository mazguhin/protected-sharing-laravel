<?php

namespace App\Rules;

use App\Repositories\RecipientRepository;
use Illuminate\Contracts\Validation\Rule;

class ActiveRecipient implements Rule
{
    public function passes($attribute, $value)
    {
        $recipientRepository = new RecipientRepository();
        $recipient = $recipientRepository->findActiveById($value);
        return !empty($recipient);
    }

    public function message()
    {
        return 'Channel is not active';
    }
}
