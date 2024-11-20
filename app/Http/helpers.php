<?php
use App\Models\User; 
use App\Models\Token\EmailToken; 
use App\Models\Token\PasswordToken; 

function deleteResetPasswordOldTokens() 
{
    PasswordToken::where('created_at', '<', now()->subDay())->delete();
}

function deleteOldEmailTokens()
{
    EmailToken::where('created_at', '<', now()->subDay())->delete();
}

function format_with_cur($amt)
{
    return "₦".number_format($amt, 2); 
}

function tableNumber( int $total ) : int
{
    if( request()->page && request()->page != 1 )
        return ( request()->page*$total ) - $total + 1;
    return 1;
}

function genGnumber()
{
    $id = 'Trb-'.mt_rand(10000000,99999999);
    if(!User::where('gnumber', $id)->exists())
        return $id;
    return genGnumber();
} 

function findByGnumber($gnumber)
{
    return User::where('gnumber', $gnumber)->first();
}

function validPhoneNumber($phoneNumber) 
{
    $phonePattern = '/^0[1-9][01]\d{8}$/';
    return preg_match($phonePattern, trim($phoneNumber)) === 1;
}