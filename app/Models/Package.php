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
        'services'
    ]; 


    /**
     * Accessor for services: Converts stored string to an array.
     */
    public function getServicesAttribute($value)
    {
        return $value ? explode(', ', $value) : [];
    }

    /**
     * Mutator for services: Converts input array to a string.
     */
    public function setServicesAttribute($value)
    {
        $this->attributes['services'] = is_array($value) && !empty($value) ? implode(', ', $value) : "";
    }
}
