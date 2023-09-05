<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        "category_id",
        "meta_title",
        "meta_keyword",
        "meta_descrip",
        "slug",
        "name",
        "description",
        
        "brand",
        "selling_price",
        "original_price",
        "category_id",
        "qty",
        "image",
        
        "featured",
        "popular",
        "status",


    ];
}
