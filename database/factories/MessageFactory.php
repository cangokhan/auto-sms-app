<?php

namespace Database\Factories;

use App\Models\Message;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'segment' => $this->faker->randomElement(['vip', 'premium']),
            'phone' => $this->faker->phoneNumber,
            'name' => $this->faker->name,
            'content' => $this->faker->sentence,
            'status' => 'pending',
            'external_message_id' => null,
            'sent_at' => null,
            'response_payload' => null,
        ];
    }
}
