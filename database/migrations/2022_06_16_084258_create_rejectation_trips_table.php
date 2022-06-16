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
        Schema::create('rejectation_trips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rejectation_id');
            $table->unsignedBigInteger('rejection_reason_id');
            $table->timestamps();
            $table->foreign("rejectation_id")->references('id')->on('rejectations')->onDelete('cascade');
            $table->foreign("rejection_reason_id")->references('id')->on('rejection_reasons')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rejectation_trips');
    }
};
