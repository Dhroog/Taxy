<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->unique();
            $table->boolean('status');
            $table->string('password');
            $table->string('fcm_token')->nullable();
            $table->enum('type',['driver','admin','customer'])->default('customer');
            $table->timestamps();
        });

        DB::table('users')->insert([
            [
                'name' => 'admin',
                'phone' => '0998789856',
                'status' => true,
                'password' => Hash::make('admin'),
                'type' => 'admin'
            ],
            [
                'name' => 'driver',
                'phone' => '0998789851',
                'status' => true,
                'password' => Hash::make('driver'),
                'type' => 'driver'
            ],
            [
                'name' => 'customer',
                'phone' => '0998789853',
                'status' => true,
                'password' => Hash::make('customer'),
                'type' => 'customer'
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
