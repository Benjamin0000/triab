<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Order extends Model
{
    use Uuids;

    protected $fillable = [
        'shop_id', 
        'orderID', 
        'staff', 
        'sub_total', 
        'vat',
        'fee', 
        'total', 
        'pay_method'
    ]; 

    public function shop()
    {
        return $this->belongsTo(Shop::class); 
    }

    public function cart()
    {
        return $this->hasMany(Cart::class); 
    }
}
