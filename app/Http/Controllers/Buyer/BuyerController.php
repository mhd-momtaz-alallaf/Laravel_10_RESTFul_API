<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        // the buyer are the user who have at least one transaction.
        $buyers = Buyer::has('transactions')->get(); // get just the buyer (from the users table) who have transactions.

        return $this->showAll($buyers);
    }

    public function show(Buyer $buyer)
    {
        return $this->showOne($buyer);
    }
}
