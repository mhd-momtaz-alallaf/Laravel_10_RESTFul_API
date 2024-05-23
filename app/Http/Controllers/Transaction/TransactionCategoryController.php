<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']); // this middleware is to protect some routes from being accessed by any user who dosn't authentecated and verified.
    }

    public function index(Transaction $transaction)
    {
        $categories = $transaction->product->categories;

        return $this->showAll($categories);
    }
}
