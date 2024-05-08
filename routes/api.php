<?php

use App\Http\Controllers\Buyer\BuyerController;
use App\Http\Controllers\Buyer\BuyerTransactionController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Seller\SellerController;
use App\Http\Controllers\Transaction\TransactionCategoryController;
use App\Http\Controllers\Transaction\TransactionController;
use App\Http\Controllers\Transaction\TransactionSellerController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * Users
 */
Route::resource('users',UserController::class)
    ->except(['create','edit']);

/**
 * Buyers
 */
Route::resource('buyers',BuyerController::class)
    ->only(['index', 'show']);
Route::resource('buyers.transactions',BuyerTransactionController::class)
    ->only(['index']);

/**
 * Sellers
 */
Route::resource('sellers',SellerController::class)
    ->only(['index','show']);

/**
 * Transactions
 */
Route::resource('transactions',TransactionController::class)
    ->only(['index','show']);

Route::resource('transactions.categories',TransactionCategoryController::class)
    ->only(['index']);
Route::resource('transactions.sellers',TransactionSellerController::class)
    ->only(['index']);

/**
 * Products
 */
Route::resource('products',ProductController::class)
    ->only(['index','show']);

/**
 * Categories
 */
Route::resource('categories',CategoryController::class)
    ->except(['create','edit']);

    
Route::fallback(function (){
    abort(404, 'API resource not found');
});