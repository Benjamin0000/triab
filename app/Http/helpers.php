<?php
use App\Models\User; 
use App\Models\Register; 
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
