<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::flushEventListeners(); // to not trigger the event listeners whene seeding the data
        \App\Models\Category::factory(30)->create();
    }
}
