<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    public function index()
    {
        // the buyer are the user who have at least one transaction.
        $buyers = Buyer::has('transactions')->get(); // get just the buyer (from the users table) who have transactions.

        return response()->json(['data' => $buyers], 200);
    }

    public function show(string $id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);

        return response()->json(['data' => $buyer], 200);
    }
}
