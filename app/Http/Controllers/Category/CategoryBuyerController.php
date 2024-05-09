<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    public function index(Category $category)
    {
        $buyers = $category->products()
            ->whereHas('transactions') // to get only the products that have at least one transaction.
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions') // first we get only the transactions to to list them all in one collection by ->collapse() function.
            ->collapse() 
            ->pluck('buyer') // second we get only the buyers of that transactions.
            ->unique('id') // without repeating.
            ->values(); // without empty records.

        return $this->showAll($buyers);
    }
}