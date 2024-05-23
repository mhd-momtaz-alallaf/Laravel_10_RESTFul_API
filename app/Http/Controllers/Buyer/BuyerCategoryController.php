<?php

namespace App\Http\Controllers\Buyer;

use App\Models\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
            ->pluck('product.categories') // every one of the products have a deffirent categories collection, but we dont want that, we want only one uinque list of categories, so we will use collapse().
            ->collapse() // will return a unique collection from the several collections (categories collection inside the transactions collection).
            ->unique('id')// to avoide repeating the same category.
            ->values(); // to avoide empty records.

        return $this->showAll($categories);
    }
}