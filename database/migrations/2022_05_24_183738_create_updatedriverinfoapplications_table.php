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
        Schema::create('updatedriverinfoapplications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('driver_id');
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('image_car')->nullable();
            $table->string('carmodel')->nullable();
            $table->string('carnumber')->nullable();
            $table->string('carcolor')->nullable();
            $table->string('image_driver')->nullable();
            $table->integer('age')->nullable();
            $table->enum('status',['accept','reject','waiting'])->default('waiting');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("driver_id")->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('updatedriverinfoapplications');
    }
};
