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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('surname');
            $table->integer('age');
            $table->boolean('available')->default(false);
            $table->double('lat')->default(0);
            $table->double('long')->default(0);
            $table->timestamps();
            $table->foreign("user_id")->references('id')->on('users')->onDelete('cascade');
        });

        DB::table('drivers')->insert([
            [
                'user_id'=> 2,
                'surname'=> 'driver',
                'age' => 29,
                'available'=>false,
                'lat' => random_int(100,100000),
                'long' => random_int(100,100000),
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
        Schema::dropIfExists('drivers');
    }
};
