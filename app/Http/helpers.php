<?php
use App\Models\User; 

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
    $id = mt_rand(10000000,99999999);
    if(!User::where('gnumber', $id)->exists())
        return $id;
    return genGnumber();
} 