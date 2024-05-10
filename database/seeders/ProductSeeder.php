<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Product::flushEventListeners(); // to not trigger the event listeners whene seeding the data
        \App\Models\Product::factory(1000)->create()->each(
            function ($product) {
        		$categories = Category::all()->random(mt_rand(1, 5))->pluck('id'); // ->pluck('id') to just get the id.

        		$product->categories()->attach($categories);
        	});
    }
}
