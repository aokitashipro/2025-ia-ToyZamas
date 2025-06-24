<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Toy;

class ToySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Toy::factory()->count(100)->create();
    }
}
