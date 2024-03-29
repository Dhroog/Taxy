<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Balance;
use App\Models\Car;
use App\Models\Code;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
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
                    $user->banned = false;
                    $user->password = Hash::make('admin');
                    $user->status = true;
                    $user->fcm_token = 'ff1DlaTMTJGB5U1B-upDe0:APA91bFmUAhRQB8rec0siaKZQv8gNMwAF50AuGxqiRjnSSdxBy6x1ESl1ostnA8gnDrnGQ2iM2-I0Cx4dsGMMfHTW66lFAY3s5F_E3jbcMj0hjrtgec_reJVhBXbu-bl46HOk7QNe--c';
                    $user->save();
                    $admin = new Admin();
                    $admin->user_id = $user->id;
                    $admin->save();
                    $code = new Code();
                    $code->code = random_int(100000,999999);
                    $code->user_id = $user->id;
                    $code->save();
                }
            );
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('Driver');
                    $user->type = 'driver';
                    $user->banned = false;
                    $user->password = Hash::make('driver');
                    $user->status = true;
                    $user->fcm_token = 'ff1DlaTMTJGB5U1B-upDe0:APA91bFmUAhRQB8rec0siaKZQv8gNMwAF50AuGxqiRjnSSdxBy6x1ESl1ostnA8gnDrnGQ2iM2-I0Cx4dsGMMfHTW66lFAY3s5F_E3jbcMj0hjrtgec_reJVhBXbu-bl46HOk7QNe--c';
                    $user->save();
                    $code = new Code();
                    $code->code = random_int(100000,999999);
                    $code->user_id = $user->id;
                    $code->save();
                    $driver = new Driver();
                    $driver ->user_id = $user->id;
                    $driver->available = true;
                    $driver ->surname = 'driver';
                    $driver ->age = 29;
                    $driver->save();
                    $car = new Car();
                    $car->driver_id = $driver->id;
                    $car->category_id = 1;
                    $car->model = 'bego';
                    $car->color = 'blue';
                    $car->number = 5060123456;
                    $car->save();
                    $balance = new Balance();
                    $balance->driver_id = $driver->id;
                    $balance->save();
                }
            );
        User::factory()->count(1)
            ->create()
            ->each(
                function ($user){
                    $user->assignRole('customer');
                    $user->password = Hash::make('customer');
                    $user->status = true;
                    $user->banned = false;
                    $user->fcm_token = 'ff1DlaTMTJGB5U1B-upDe0:APA91bFmUAhRQB8rec0siaKZQv8gNMwAF50AuGxqiRjnSSdxBy6x1ESl1ostnA8gnDrnGQ2iM2-I0Cx4dsGMMfHTW66lFAY3s5F_E3jbcMj0hjrtgec_reJVhBXbu-bl46HOk7QNe--c';
                    $user->save();
                    $code = new Code();
                    $code->code = random_int(100000,999999);
                    $code->user_id = $user->id;
                    $code->save();
                    $customer = new Customer();
                    $customer->user_id = $user->id;
                    $customer->save();

                }
            );
    }
}
