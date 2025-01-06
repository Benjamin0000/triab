<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class StockHistory extends Model
{
    use Uuids; 

    protected $fillable = [
        'product_id', 
        'amt',
        'type', 
        'desc', 
        'cost_price', 
        'selling_price'
    ]; 
}
