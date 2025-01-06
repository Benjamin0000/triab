<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Cart extends Model
{
    use Uuids;

    protected $fillable = [
        'order_id',
        'product_id',
        'name',
        'price',
        'qty'
    ]; 
}