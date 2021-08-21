<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class PasswordHelper
{
    public function generatePassword($length = 8): string
    {
        return Str::random($length);
    }
}
