<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentSumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'year' => $this->faker->numberBetween($min = 2010, $max = 2030),
            'month' => $this->faker->numberBetween($min = 1, $max = 12),
            'total_price' => $this->faker->numberBetween($min = 0, $max = 200000)
        ];
    }
}
