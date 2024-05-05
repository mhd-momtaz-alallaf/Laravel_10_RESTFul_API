<?php

namespace Database\Factories;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seller = Seller::has('products')->get()->random(); // sellers have at least one product, ->random() gets only one.  
        $buyer = User::all()->except($seller->id)->random();// any user except the seller of the product 

        return [
            'quantity' => fake()->numberBetween(1, 3),
            'buyer_id' => $buyer->id,
            'product_id' => $seller->products->random()->id,
            // User::inRandomOrder()->first()->id
        ];
    }
}
