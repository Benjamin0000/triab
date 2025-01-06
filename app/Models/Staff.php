<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Staff extends Model
{
    use Uuids;

    protected $fillable = [
        'shop_id',
        'name',
        'pass_code',
        'admin', 
        'token', 
        'status'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class); 
    }
}
