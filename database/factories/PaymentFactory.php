<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'user_id' => $this->faker->numberBetween($min = 1, $max = 100),
            'year' => $this->faker->numberBetween($min = 2010, $max = 2030),
            'month' => $this->faker->numberBetween($min = 1, $max = 12),
            'price' => $this->faker->numberBetween($min = 0, $max = 200000)
        ];
    }
}
