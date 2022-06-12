<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('Super-Admin');
                    $user->type = 'admin';
                    $user->password = Hash::make('admin');
                    $user->status = true;
                    $user->save();
                }
            );
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('Driver');
                    $user->type = 'driver';
                    $user->password = Hash::make('driver');
                    $user->status = true;
                    $user->save();
                    $driver = new Driver();
                    $driver ->user_id = $user->id;
                    $driver ->surname = 'driver';
                    $driver ->age = 29;
                    $driver->save();
                }
            );
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('customer');
                    $user->password = Hash::make('customer');
                    $user->status = true;
                    $user->save();

                }
            );
    }
}
