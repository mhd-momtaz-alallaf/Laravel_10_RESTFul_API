<?php

namespace App\Http\Controllers\Category;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions') // to get only the products that have at least one transaction.
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse(); // to return only one collection of the transactions.

        return $this->showAll($transactions);
    }
}