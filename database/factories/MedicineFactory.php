<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Medicine>
 */
class MedicineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'scientific_name' => $this->faker->streetName , 
            'commercial_name' => $this->faker->domainName , 
            'max_amount' => $this->faker->randomNumber(3) ,
            'price' => $this->faker->randomNumber(5) ,
        ];
    }
}
