<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class TrxHistory extends Model
{
    use Uuids; 
    
    protected $fillable = [
        'user_id', 
        'name', 
        'type', 
        'desc', 
        'amt'
    ]; 
}
