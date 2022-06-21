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
            'image' => $this->faker->imageUrl(),
            'status' => $this->faker->boolean(),
            'banned' => $this->faker->boolean(),
            'fcm_token' => 'cw7BskxnSZKu1UAQd6hIIh:APA91bE74j1vRVX5uuRDBoeRzFhFqWB5Ep8WH_8ZzcDYSPfnNQ5wYyGaiTm8k9cKbWm5gcLcOfV7ruyun02EWcpvxaDgW0ci0iC1AXRHfcLrN7CrWyE3muGj4Pv5XkE9P7Vh_l-5DXQB',


        ];
    }

    public function admin(): UserFactory
    {
        return $this->state([
            'type' => 'admin'
        ]);
    }

    public function customer(): UserFactory
    {
        return $this->state([
            'type' => 'customer'
        ]);
    }

    public function driver(): UserFactory
    {
        return $this->state([
            'type' => 'driver'
        ]);
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
