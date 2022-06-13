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
            $table->unsignedBigInteger('customer_id');
            $table->string('s_location');
            $table->string('e_location');
            $table->double('s_lat');
            $table->double('s_long');
            $table->double('e_lat');
            $table->double('e_long');
            $table->double('distance');
            $table->string('duration');
            $table->string('s_date')->nullable();
            $table->string('e_date')->nullable();
            $table->double('cost')->default(0);
            $table->boolean('accepted')->default(false);
            $table->boolean('canceled')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->boolean('started')->default(false);
            $table->boolean('delved')->default(false);
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
