<?php
use App\Models\User;
use App\Models\Register;
use App\Models\TrxHistory;
use App\Models\Token\EmailToken;
use App\Models\Token\PasswordToken;
use App\Models\WheelGlobal;
use App\Models\WheelLocal;

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

function run_wheel_global()
{
    $stages = 5;
    $stage_amts = [2000, 7000, 22000, 57000, 192000];
    $max_times = 5;

    for ($stage = 1; $stage <= $stages; $stage++) {
        $giver = WheelGlobal::where([
            ['stage', $stage],
            ['giving', 1],
            ['status', 0]
        ])->oldest()->first();

        if (!$giver) {
            continue; // No giver available, skip to the next stage
        }

        $receiver = WheelGlobal::where([
            ['stage', $stage],
            ['giving', 0],
            ['status', 0]
        ])->oldest()->first();

        if (!$receiver) {
            continue; // No receiver available, skip to the next stage
        }

        $amount = $stage_amts[$stage - 1];

        // Process giver
        $giver->pending_balance -= $amount;
        $giver->giving = 0;
        $giver->save();

        // Process receiver
        $receiver->pending_balance += $amount;
        $receiver->times_received++;

        if ($receiver->times_received >= $max_times) {
            $next_stage = $stage + 1;

            if ($next_stage > $stages) {
                // Final stage: Transfer all pending balance to main balance
                $receiver->main_balance += $receiver->pending_balance;
                $receiver->pending_balance = 0;
                $receiver->status = 1; // Mark as completed
            } else {
                // Advance to the next stage
                $receiver->main_balance += $receiver->pending_balance - $stage_amts[$stage];
                $receiver->pending_balance = $stage_amts[$stage];
                $receiver->stage = $next_stage;
                $receiver->giving = 1; // Become a giver in the next stage
            }
        }

        $receiver->save();
    }
}





function run_wheel_local()
{
    $stages = 5;
    $stage_amts = [2000, 7000, 22000, 57000, 192000];
    $max_times = 5;

    for ($stage = 1; $stage <= $stages; $stage++) {
        $givers = WheelLocal::where([
            ['stage', $stage],
            ['giving', 1],
            ['status', 0]
        ])->oldest()->get();

        foreach ($givers as $giver) {
            // Traverse the 3x3 matrix to find the eligible receiver
            $receiver = find_eligible_receiver($giver, $stage);

            if ($receiver) {
                $amount = $stage_amts[$stage - 1];

                // Process giver
                $giver->pending_balance -= $amount;
                $giver->giving = 0;
                $giver->save();

                // Process receiver
                $receiver->pending_balance += $amount;
                $receiver->times_received++;

                if ($receiver->times_received >= $max_times) {
                    $next_stage = $stage + 1;

                    if ($next_stage > $stages) {
                        // Final stage: Transfer all pending balance to main balance
                        $receiver->main_balance += $receiver->pending_balance;
                        $receiver->pending_balance = 0;
                        $receiver->status = 1; // Mark as completed
                    } else {
                        // Advance to the next stage
                        $receiver->main_balance += $receiver->pending_balance - $stage_amts[$stage];
                        $receiver->pending_balance = $stage_amts[$stage];
                        $receiver->stage = $next_stage;
                        $receiver->giving = 1; // Become a giver in the next stage
                    }
                }

                $receiver->save();
            }
        }
    }
}

/**
 * Finds the most eligible receiver within the 3x3 matrix.
 *
 * @param WheelLocal $giver
 * @param int $stage
 * @return WheelLocal|null
 */
function find_eligible_receiver(WheelLocal $giver, int $stage)
{
    // Start with the `placed_under` value to find receivers in the same matrix
    $queue = collect([$giver->placed_under]);
    $visited = collect(); // To avoid re-checking the same users

    while ($queue->isNotEmpty()) {
        $current_id = $queue->shift();

        if ($visited->contains($current_id)) {
            continue;
        }
        $visited->push($current_id);

        // Find eligible receiver directly placed under this user
        $receiver = WheelLocal::where([
            ['placed_under', $current_id],
            ['stage', $stage],
            ['giving', 0],
            ['status', 0]
        ])->oldest()->first();

        if ($receiver) {
            return $receiver; // Found the most eligible receiver
        }

        // Add all users placed under the current user to the queue for further traversal
        $queue = $queue->merge(
            WheelLocal::where('placed_under', $current_id)->pluck('id')->toArray()
        );
    }

    return null; // No eligible receiver found
}

