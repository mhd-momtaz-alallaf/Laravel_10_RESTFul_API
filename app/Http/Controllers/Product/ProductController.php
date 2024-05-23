<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index', 'show']); // this middleware is to protect some routes from being accessed by any user who dosn't authentecated and verified.
    }

    public function index()
    {
        $products = Product::all();

        return $this->showAll($products);
    }

    public function show(Product $product)
    {
        return $this->showOne($product);
    }
}
