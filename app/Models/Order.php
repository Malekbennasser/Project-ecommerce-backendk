<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $tabel='orders';
    protected $fillable =[

        'firstname',
        'lastname',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zipcode',
        'payment_id',
        'payment_mode',
        'tracking_no'
    ];



    public function orderitems(){

        return $this->hasMany(Orderitems::class, 'order_id', 'id');
    }
}
