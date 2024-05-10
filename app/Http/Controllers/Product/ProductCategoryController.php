<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    public function update(Product $product, Category $category)
    {
        //attach, sync, syncWithoutDetach
        $product->categories()->syncWithoutDetaching($category); // or we can use syncWithoutDetaching($request->category);

        return $this->showAll($product->categories);
    }

    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category)){
            return $this->errorResponse('The spcefied category is not a category of this product!', 404);
        }

        $product->categories()->detach($category);

        return $this->showAll($product->categories);
    }
}