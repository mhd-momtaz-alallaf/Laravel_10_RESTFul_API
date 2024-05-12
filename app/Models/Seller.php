<?php

namespace App\Models;

use App\Http\Resources\SellerResource;
use App\Models\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
    use HasFactory;

    public $modelResource = SellerResource::class;

    protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new SellerScope);
	}

    public function products()
    {
    	return $this->hasMany(Product::class);
    }
}
