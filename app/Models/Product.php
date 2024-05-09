<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    const AVAILABLE_PRODUCT = 'available';
	const UNAVAILABLE_PRODUCT = 'unavailable';

    protected $hidden = [ // to hide the pivot field from the response
        'pivot'
    ];

    protected $dates = ['deleted_at']; // for softDeleting.

    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id', // the table that have the forignkey will have a ->belongsTo() relation with the table of the forignkey (with the Seller) the Prodect ->belongsTo(Seller) 
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function isAvailable()
    {
    	return $this->status == Product::AVAILABLE_PRODUCT;
    }
}

