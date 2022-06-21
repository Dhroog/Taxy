<?php

namespace Database\Seeders;

use App\Models\Balance;
use App\Models\Car;
use App\Models\Category;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;

class testSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //make users type customer
         User::factory()->count(25)->has(Customer::factory()->count(1))->create()->each(function ($user){
            $user->assignRole('Customer');
            //make code for users
            Code::factory()->count(1)->for($user)->create();
            //make category
            $category = Category::factory()->create();
            //make user type driver
            $us = User::factory()->driver()->has(Code::factory())->create();
             $us->assignRole('Driver');
             //make driver
            $driver = Driver::factory()->has(Balance::factory())->for($us)->create();
            //make car for driver and category
            Car::factory()->driver($driver->id)->for($category)->create();
            //make trip for user
            Trip::factory()->customer($user->name,$user->phone,$user->image)->category($category->id)->count(6)->for($user)->create();

        });




    }
}
