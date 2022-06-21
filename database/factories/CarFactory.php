<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'driver_id' => 1,
            'model' => $this->faker->word(),
            'number' => $this->faker->randomNumber(6),
            'color' => $this->faker->colorName(),
            'image'=> $this->faker->imageUrl()
        ];
    }

    public function driver($id): CarFactory
    {
        return $this->state([
            'driver_id' => $id,
        ]);
    }
}
