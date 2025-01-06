<?php
use App\Models\User;
use App\Models\Register;
use App\Models\TrxHistory;
use App\Models\Token\EmailToken;
use App\Models\Token\PasswordToken;
use App\Models\WheelGlobal;
use App\Models\State; 
use App\Models\Shop; 
use App\Models\Product; 
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


CONST CATEGORY = 0;
CONST PRODUCT = 1;

//package services code. 
const E_SHOP = 'e-shop';
const HOTEL = 'hotel';
const RESTAURANT = 'restaurant';
const LOGISTICS = 'logistics';

const CREDIT = 1;
const DEBIT = 0;
const wheel_one = [
    [
        'amt'=>1500,
        'total_refs'=>2, 
        'times'=>6
    ], 
    [
        'amt'=>5000,
        'total_refs'=>6,
        'times'=>6
    ],
    [
        'amt'=>14_000,
        'total_refs'=>14,
        'times'=>6 
    ], 
    [
        'amt'=>54_000, 
        'total_refs'=>16, 
        'times'=>2 
    ]
]; 

const wheel_two = [
    [
        'amt'=>2500,
        'total_refs'=>20,
        'times'=>6
    ], 
    [
        'amt'=>8_000,
        'total_refs'=>28,
        'times'=>6
    ],
    [
        'amt'=>25_000,
        'total_refs'=>30, 
        'times'=>6
    ], 
    [
        'amt'=>100_000, 
        'total_refs'=>34,
        'times'=>2
    ]
];

const wheel_three = [
    [
        'amt'=>4500,
        'total_refs'=>42,
        'times'=>6
    ], 
    [
        'amt'=>15_000,
        'total_refs'=>44,
        'times'=>6
    ],
    [
        'amt'=>42_000,
        'total_refs'=>48, 
        'times'=>6
    ], 
    [
        'amt'=>162_000, 
        'total_refs'=>56, 
        'times'=>2
    ]
];

function generateReceiptNumber($prefix = 'RCPT', $length = 8) {
    // Generate a random unique ID based on the current time
    $uniqueId = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, $length - strlen($prefix)));

    // Combine prefix and unique ID
    $no = $prefix . $uniqueId;

    $exists = Order::where('orderID', $no)->exists();

    if($exists)
        return generateReceiptNumber(); 
    return $no; 
}


function all_services()
{
    return [
        E_SHOP,
        HOTEL,
        RESTAURANT,
        LOGISTICS
    ]; 
}

function default_user()
{
    $email = 'water@gmail.com'; 
    return $user = User::where('email', $email)->first(); 
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

function format_with_cur($amt, $dp=2)
{
    return currency_symbol().number_format($amt, $dp); 
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


function genStoreID()
{
    $id = 'Trbshop-'.mt_rand(10000000,99999999);
    if(!Shop::where('storeID', $id)->exists())
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
        $user->creditPV($pv);
    }
    return credit_package_and_PV_ref_commission($user, $amt, $step + 1);   
}


function placeUserInMatrix(User $user, User $referrer)
{
    // Find placement within the referrer's tree
    $placement = findPlacementUser($referrer);

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

        if ($childCount < 2) {
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

function set_stage_left_funds($amt)
{
    $total = (float)get_register('level_left_funds');
    $total += $amt;
    set_register('level_left_funds', $total);
}


function run_wheel_funding(array $levels, int $stage, float $next_stage_amt, float $exit_amt)
{
    $total_levels = 4;
    for ($level = 1; $level <= $total_levels; $level++) {

        $data = $levels[$level - 1];
        $amount = $data['amt'];
        $total_refs = $data['total_refs'];
        $max_times = $data['times'];

        // Fetch giver
        $giver = WheelGlobal::where([
            ['stage', $stage],
            ['level', $level],
            ['giving', 1],
        ])->oldest()->first();

        if (!$giver) {
            // No giver available, skip to the next level
            continue;
        }

        // Fetch receiver
        $receiver = WheelGlobal::where([
            ['stage', $stage],
            ['level', $level],
            ['giving', 0],
            ['total_refs', '>=', $total_refs],
            ['times_received', '<', $max_times],
        ])->oldest()->first();  //====>use updated for ordering

        if (!$receiver) {
            // No receiver available, skip to the next level
            continue;
        }

        // Use database transactions to ensure atomicity
        DB::transaction(function () use (
            $giver,
            $receiver,
            $amount,
            $level,
            $total_levels,
            $next_stage_amt,
            $exit_amt
        ) {
            // Process giver
            $giver->pending_balance -= $amount;
            $giver->giving = 0;
            $giver->save();

            // Process receiver
            $receiver->pending_balance += $amount;
            $receiver->times_received++;

            if ($receiver->times_received >= $max_times) {
                $next_level = $level + 1;

                if ($next_level > $total_levels) {
                    // Advance to the next stage
                    $receiver->stage++;
                    $receiver->level = 1;
                    $amt_left = $receiver->pending_balance - $exit_amt - $next_stage_amt;
                    $receiver->main_balance += $amt_left;
                    $receiver->total_received += $amt_left;
                    $receiver->pending_balance = $next_stage_amt;
                    set_stage_left_funds($exit_amt);
                    $receiver->credit_sponsor_commission($amt_left); //credit the sponsor commission
                } else {
                    // Advance to the next level
                    $next_level_amt = $levels[$level]['amt'];
                    $amt_left = $receiver->pending_balance - $next_level_amt;
                    $receiver->main_balance += $amt_left;
                    $receiver->total_received += $amt_left; 
                    $receiver->pending_balance = $next_level_amt;
                    $receiver->level = $next_level;
                    $receiver->credit_sponsor_commission($amt_left);
                }
                $receiver->times_received = 0;
                if (!$receiver->origin) {
                    $receiver->giving = 1; // Become a giver in the next level
                }
            }

            $receiver->save();
        });
    }
}

function run_wheel_stage_one()
{
    $levels = wheel_one; 
    $stage = 1; 
    $next_stage_amt = 2500;
    $exit_amt = 50_000; //amount to subtract in the last stage
    run_wheel_funding($levels, $stage, $next_stage_amt, $exit_amt); 
}


function run_wheel_stage_two()
{
    $levels = wheel_two; 
    $stage = 2; 
    $next_stage_amt = 4500;
    $exit_amt = 120_000;
    run_wheel_funding($levels, $stage, $next_stage_amt, $exit_amt); 
}

function run_wheel_stage_three()
{
    $levels = wheel_three; 
    $stage = 3; 
    $next_stage_amt = 0; 
    $exit_amt = 200_000; 
    run_wheel_funding($levels, $stage, $next_stage_amt, $exit_amt); 
}

function upload_poster($file, $maxSizeInKB)
{
    $fileSize = $file->getSize(); // size in bytes
    $fileSizeInKB = $fileSize / 1024;

    if ($fileSizeInKB > $maxSizeInKB) 
        return ['error'=>"The image must not exceed $maxSizeInKB Kb in size."];

    $path = $file->storePublicly('images', 'public');
    return ['path'=>$path];
}

function delete_poster($images)
{
    foreach($images as $image){
        if(Storage::disk('public')->exists($image))
            Storage::disk('public')->delete($image);
    }
}

function validateStateWithCity(string $state, string $city) : bool
{
    $state = State::where('name', $state)->first();

    if($state){
        $city = State::where([ 
            ['name', $city],
            ['parent_id', $state->id]
        ])->first();

        if($city)
            return true; 
    }

    return false; 
}


function truncateText($text, $maxLength, $appendEllipsis = true) {
    // Check if text length exceeds the maximum length
    if (strlen($text) > $maxLength) {
        // Truncate the text to the maximum length
        $truncated = substr($text, 0, $maxLength);
        
        // Ensure it doesn't cut a word in half
        $truncated = substr($truncated, 0, strrpos($truncated, ' '));
        
        // Append ellipsis if required
        if ($appendEllipsis) {
            $truncated .= '...';
        }
        
        return $truncated;
    }
    
    // Return original text if it's within the limit
    return $text;
}



function get_product_category_gen(string $parent_id): array
{
    $categories = [];
    $product = Product::find($parent_id);

    while ($product) {
        $categories[] = $product->name;
        $parent_id = $product->parent_id;
        $product = $parent_id ? Product::find($parent_id) : null;
    }

    return array_reverse($categories);
}

function sum_total($items)
{
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['qty'];
    }
    return $total; 
}