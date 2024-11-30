<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    protected $fillable = [
        'name',
        'cost',
        'discount',
        'max_gen',
        'services',
        'cashback'
    ]; 


    /**
     * Accessor for services: Converts stored string to an array.
     */
    public function getServicesAttribute($value)
    {
        return json_decode($value, true);
    }

    /**
     * Mutator for services: Converts an associative array to a JSON string.
     */
    public function setServicesAttribute($value)
    {
        $this->attributes['services'] = json_encode($value); // Encode to JSON before saving
    }
}
