<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    protected $fillable = [
        'name',
        'icon'
    ]; 

    public function total_shops()
    {
        return $this->hasMany(Shop::class, 'category_id')->count(); 
    }
}
