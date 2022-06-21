<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            's_location' => $this->faker->streetName(),
            'e_location' => $this->faker->streetName(),
            's_lat' => $this->faker->latitude(),
            's_long' => $this->faker->longitude(),
            'e_lat' => $this->faker->latitude(),
            'e_long' => $this->faker->longitude(),
            'distance' => $this->faker->randomNumber(3),
            'duration' => $this->faker->numberBetween(15,90),
        ];
    }

    public function customer($name,$phone,$image): TripFactory
    {
        return $this->state([
            'customer_name' => $name,
            'customer_phone' => $phone,
            'customer_image' => $image
        ]);
    }

    public function driver($name,$phone,$image): TripFactory
    {
        return $this->state([
            'driver_name' => $name,
            'driver_phone' => $phone,
            'driver_image' => $image
        ]);
    }

    public function category($category_id): TripFactory
    {
        return $this->state([
            'category_id' => $category_id,
        ]);
    }
}
