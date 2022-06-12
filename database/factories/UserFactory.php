<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
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
            'password' => Hash::make(Str::random(10)), // password
            'phone' => random_int(1000000000,9999999999),
            'image' => null,
            'status' => $this->faker->boolean(),
            'fcm_token' => null,


        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    /*
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
    */
}
