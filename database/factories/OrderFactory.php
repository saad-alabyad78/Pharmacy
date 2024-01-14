<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElements(['pending','on_its_way','completed']) ,
            'date' => $this->faker->date(),
            'paid' => $this->faker->boolean(false),
            'total_price' => $this->faker->randomNumber(5) ,
        ];
    }
}
