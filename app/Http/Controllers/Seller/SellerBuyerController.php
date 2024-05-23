<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerBuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Seller $seller)
    {
        $buyers = $seller->products()
            ->whereHas('transactions')
            ->with('transactions.buyer') // nested eger loading.
            ->get()
            ->pluck('transactions')
            ->collapse() // get the resoults as only one collection of transactions.
            ->pluck('buyer') // returns only the buyer
            ->unique('id')
            ->values();

        return $this->showAll($buyers);
    }
}