<?php
use App\Models\User; 
use App\Models\Register; 
use App\Models\Token\EmailToken; 
use App\Models\Token\PasswordToken; 

//package services code. 
const E_SHOP = 1;
const HOTEL = 2; 
const RESTAURANT = 3; 
const LOGISTICS = 4; 
const HEALTH = 5; 

function all_services()
{
    return [
        'e-shop'=>E_SHOP, 
        'hotel'=>HOTEL,
        'restaurant'=>RESTAURANT, 
        'logistics'=>LOGISTICS, 
        'health_insurance'=>HEALTH
    ]; 
}


function make_readable($text)
{
    $text = str_replace('_', ' ', $text);
    return $text = ucwords($text);
}


function getPositionWithSuffix(int $number): string
{
    // Handle special cases for numbers ending in 11, 12, or 13
    if ($number % 100 >= 11 && $number % 100 <= 13) {
        return $number . 'th';
    }

    // Get the last digit of the number
    $lastDigit = $number % 10;

    // Determine the suffix based on the last digit
    switch ($lastDigit) {
        case 1:
            return $number . 'st';
        case 2:
            return $number . 'nd';
        case 3:
            return $number . 'rd';
        default:
            return $number . 'th';
    }
}



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
    return currency_symbol().number_format($amt, 2); 
}

function currency_symbol()
{
    return "₦";
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

function set_register($name, $value="")
{
    if( $reg = Register::where('name', $name)->first() ){
        $reg->value = $value; 
        $reg->save(); 
        return ; 
    }
    Register::create([
        'name'=>$name,
        'value'=>$value
    ]); 
}

function get_register($name)
{
    $reg = Register::where('name', $name)->first(); 
    if(!$reg)
        $reg = Register::create(['name'=>$name]); 
    return $reg->value; 
}
