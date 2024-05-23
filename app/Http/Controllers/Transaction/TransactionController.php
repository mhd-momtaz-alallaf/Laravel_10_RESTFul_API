<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        $tramsactions = Transaction::all();

        return $this->showAll($tramsactions);
    }

    public function show(Transaction $transaction)
    {
        return $this->showOne($transaction);
    }
}
