<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    // to store the new transaction of the buyer of the product.
    public function store(Request $request, Product $product, User $buyer) // we used User not a Buyer model because maybe this is the first user transaction (befor he became a Buyer).
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('The buyer must be different from the seller', 409);
        }

        if (!$buyer->isVerified()) {
            return $this->errorResponse('The buyer must be a verified user', 409);
        }

        if (!$product->seller->isVerified()) {
            return $this->errorResponse('The seller must be a verified user', 409);
        }

        if (!$product->isAvailable()) {
            return $this->errorResponse('The product is not available', 409);   
        }

        return DB::transaction(function() use ($request, $product, $buyer) { // DB::transaction for protect the quntity from any other updating untel the whole opporations is done (so it will all success or it will all fail). 
            if ($product->quantity < $request->quantity) {
                return $this->errorResponse('The product does not have enough units for this transaction', 409);   
            }

            $product->quantity -= $request->quantity;
            // we will handling the Products Availability Using Events.
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}