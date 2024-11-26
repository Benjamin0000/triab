<?php
use App\Models\User; 
use App\Models\Register; 
use App\Models\TrxHistory; 
use App\Models\Token\EmailToken; 
use App\Models\Token\PasswordToken; 

//package services code. 
const E_SHOP = 1;
const HOTEL = 2; 
const RESTAURANT = 3; 
const LOGISTICS = 4; 
const HEALTH = 5; 

const CREDIT = 1;
const DEBIT = 0;


function default_user()
{
    $email = 'water@gmail.com'; 
    return $user = User::where('email', $email)->first(); 
}

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

function calculate_pct($amt, $pct)
{
    return ( $pct/100 ) * $amt; 
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


function ref_commission()
{
    return explode(',', get_register('cashback'));
}

function PV_commission()
{
    return explode(',', get_register('pv'));
}


function credit_package_and_PV_ref_commission(User $user, float $amt, int $step=1)
{
    $com = ref_commission();
    $max_gen = count($com);

    if($step > $max_gen) return;
        $pv = PV_commission();

    $id = $user->ref_by;

    if(!$id) return; 

    $user = User::find($id); 

    if(!$user) return; 

    $package = $user->package; 

    if($package && $package->max_gen >= $step){
        $com = $com[$step - 1];
        $pv = $pv[$step - 1];
        $reward = calculate_pct($amt, $com);
        $desc = getPositionWithSuffix($step).' Gen. commission'; 

        $user->credit_main_balance($reward, 'referral_com', $desc); 
        $user->credit_pv($pv);
    }
    return credit_package_and_PV_ref_commission($user, $amt, $step + 1);   
}


function placeUserInMatrix(User $user, User $referrer)
{
    // Find placement within the referrer's tree
    $placement = $this->findPlacementUser($referrer);

    if ($placement) {
        // Update the user's placed_under column
        $user->placed_under = $placement->id;
        $user->save();
    } else {
        // Handle edge case: no valid placement found
        // (Optional: assign to admin or root user, or log the error)
    }
}

function findPlacementUser(User $referrer)
{
    // Start with the referrer
    $queue = [$referrer];
    
    while (!empty($queue)) {
        $current = array_shift($queue);

        // Check if the current user has fewer than 3 children
        $childCount = User::where('placed_under', $current->id)->count();

        if ($childCount < 3) {
            return $current; // Found a valid placement
        }

        // Add current user's children to the queue for further checks
        $children = User::where('placed_under', $current->id)->get();
        foreach ($children as $child) {
            $queue[] = $child;
        }
    }
    return null; // No valid placement found
}


function run_placement()
{
    $users = User::whereNull('placed_under')->get();
    foreach($users as $user){
        $referrer = User::find($user->ref_by);
        if($referrer)
            placeUserInMatrix($user, $referrer); 
    }
}