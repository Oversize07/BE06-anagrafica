<?php

namespace Database\Seeders;

use App\Models\Famiglia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FamigliaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Famiglia::factory(10)->create();
    }
}
