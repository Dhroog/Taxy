<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jobapplication>
 */
class JobapplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'surname' => $this->faker->lastName(),
            'age' => $this->faker->numberBetween(18,50),
            'carmodel' => $this->faker->word(),
            'carnumber' => $this->faker->randomNumber(6),
            'carcolor' => $this->faker->colorName(),
            'image' => $this->faker->imageUrl()
        ];
    }
}
