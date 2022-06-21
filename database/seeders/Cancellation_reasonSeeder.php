<?php

namespace Database\Seeders;

use App\Models\Cancellation_reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Cancellation_reasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cancellation_reason::factory()->count(50)->create();
    }
}
