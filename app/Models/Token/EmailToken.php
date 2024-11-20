<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class EmailToken extends Model
{
    use Uuids; 

    protected $table = 'verify_email_tokens'; 

    protected $fillable = [
        'email'
    ]; 
}
