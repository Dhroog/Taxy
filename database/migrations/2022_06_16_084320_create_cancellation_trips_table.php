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
        Schema::create('cancellation_trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trip_id');
            $table->unsignedBigInteger('cancellation_reason_id');
            $table->timestamps();
            $table->foreign("trip_id")->references('id')->on('trips')->onDelete('cascade');
            $table->foreign("cancellation_reason_id")->references('id')->on('cancellation_reasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cancellation_trips');
    }
};
