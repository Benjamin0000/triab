<?php

namespace App\Models\Token;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class PasswordToken extends Model
{
    use Uuids; 

    protected $table = 'password_reset_tokens'; 

    protected $fillable = [
        'email'
    ]; 
}