<?php

namespace App\Models;

use App\Http\Resources\BuyerResource;
use App\Models\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User
{
    use HasFactory;

    public $modelResource = BuyerResource::class;

    protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new BuyerScope);
	}

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }
}
