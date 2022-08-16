<?php

namespace Database\Seeders;

use App\Models\Rejection_reason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Rejection_reasonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Rejection_reason::factory()->count(10)->create();
    }
}
