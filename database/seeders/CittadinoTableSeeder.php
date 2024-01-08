<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Cittadino;
use Illuminate\Database\Seeder;

class CittadinoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cittadino::factory(10)->create();
    }
}
