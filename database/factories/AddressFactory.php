<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 0,

            'state' => 'zachodniopomorskie',
            'county' => 'drawski',
            'city' => 'Czaplinek',
            'zip' => '78-550',
            'street' => 'Długa',
            'house_number' => fake()->numberBetween(1,100),
            'latitude' => 53.559028,
            'longitude' => 16.231894
        ];
    }
}
