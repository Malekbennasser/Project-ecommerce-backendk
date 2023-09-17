<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orderitems extends Model
{
    use HasFactory;

    protected $tabel='orderitems';
    protected $fillable =[

            'order_id',
            'product_id',
            'qty',
            'price',
    ];
}
