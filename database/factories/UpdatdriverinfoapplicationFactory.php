<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class UpdatdriverinfoapplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'surname' => $this->faker->lastName(),
            'image_car' => $this->faker->imageUrl(),
            'carmodel' => $this->faker->word(),
            'carnumber' => $this->faker->randomNumber(6),
            'carcolor' => $this->faker->colorName(),
            'age' => $this->faker->numberBetween(18,50),
        ];
    }
}
