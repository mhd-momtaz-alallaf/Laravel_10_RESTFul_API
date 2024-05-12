<?php

namespace App\Models;

use App\Http\Resources\CategoryResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $modelResource = CategoryResource::class;

    protected $hidden = [ // to hide the pivot field from the response
        'pivot'
    ];

    protected $dates = ['deleted_at']; // for softDeleting.

    protected $fillable = [
    	'name',
    	'description',
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class); // ->belongsToMany() because the Category and the product models have many to many relationship
    }
}
