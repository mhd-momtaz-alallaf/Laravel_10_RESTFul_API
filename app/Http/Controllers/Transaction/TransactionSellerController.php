<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionSellerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Transaction $transaction)
    {
        $seller = $transaction->product->seller;

        return $this->showOne($seller);
    }
}
