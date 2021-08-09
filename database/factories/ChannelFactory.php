<?php

namespace Database\Factories;

use App\Models\Channel;
use Illuminate\Database\Eloquent\Factories\Factory;

class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    public function definition()
    {
        return [
            'title' => 'channel title' . uniqid(),
            'name' => 'channel_name' . uniqid(),
            'is_active' => 1,
        ];
    }

    public function inactive()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => 0,
            ];
        });
    }
}
