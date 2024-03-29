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
        Schema::create('jobapplications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('surname');
            $table->integer('age');
            $table->string('carmodel');
            $table->string('carcolor');
            $table->string('carnumber');
            $table->string('image_car');
            $table->string('image');
            $table->enum('status',['accept','reject','waiting'])->default('waiting');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jobapplications');
    }
};
