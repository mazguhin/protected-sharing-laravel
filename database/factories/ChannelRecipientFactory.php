<?php

namespace Database\Factories;

use App\Models\ChannelRecipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelRecipientFactory extends Factory
{
    protected $model = ChannelRecipient::class;

    public function definition()
    {
        return [
            'data' => uniqid(),
        ];
    }
}
