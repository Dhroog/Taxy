<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->double('distance');
            $table->string('duration');
            $table->double('cost')->default(0);
            $table->string('s_location');
            $table->string('e_location');
            $table->double('s_lat');
            $table->double('s_long');
            $table->double('e_lat');
            $table->double('e_long');
            $table->string('s_date')->nullable();
            $table->string('e_date')->nullable();
            $table->boolean('accepted')->default(false);
            $table->boolean('canceled')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->boolean('started')->default(false);
            $table->boolean('ended')->default(false);
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->string('customer_image')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('driver_phone')->nullable();
            $table->string('driver_image')->nullable();
            $table->double('driver_rate')->default(0);
            $table->double('customer_rate')->default(0);
            $table->string('notice_cancele')->nullable();
            $table->string('car_color')->nullable();
            $table->string('car_number')->nullable();
            $table->string('car_model')->nullable();
            $table->timestamps();
            $table->softDeletes();
           // $table->foreign("driver_id")->references('id')->on('drivers')->onDelete('cascade');
            //$table->foreign("customer_id")->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trips');
    }
};
