<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at']; // for softDeleting.

    protected $fillable = [
    	'quantity',
    	'buyer_id', // the table that have the forignkey will have a ->belongsTo() relation with the table of the forignkey (with the Buyer), the Transaction ->belongsTo(Buyer) 
    	'product_id', // the Transaction ->belongsTo(Product) 
    ];

    public function buyer()
    {
    	return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
