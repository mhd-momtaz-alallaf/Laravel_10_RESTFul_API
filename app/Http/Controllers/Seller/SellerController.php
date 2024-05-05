<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    public function index()
    {
        $sellers = Seller::has('products')->get();

        return response()->json(['data' => $sellers], 200);
    }

    public function show(string $id)
    {
        $seller = Seller::has('products')->FindOrFail($id);

        return response()->json(['data' => $seller], 200);
    }
}
