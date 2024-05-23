<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller') // eger loading.
            ->get()
            ->pluck('product.seller') // just the sellers.
            ->unique('id') // without repeating the seller.
            ->values(); // without empty records.

        return $this->showAll($sellers);
    }
}