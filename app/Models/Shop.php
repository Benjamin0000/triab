<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Shop extends Model
{
    use Uuids; 

    protected $fillable = [
        'user_id', 
        'storeID', 
        'category_id', 
        'logo',
        'name', 
        'state', 
        'city', 
        'address', 
        'description'
    ]; 
}
