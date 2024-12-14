<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB; 
use Laravel\Sanctum\HasApiTokens; 
use App\Traits\Uuids;

class User extends Authenticatable
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, Uuids, HasApiTokens;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function package()
    {
        return $this->belongsTo(Package::class); 
    }

    public function credit_main_balance($amt, $source, $desc)
    {
        $this->main_balance += $amt;
        TrxHistory::create([
            'user_id'=>$this->id,
            'name'=>$source,
            'type'=>CREDIT,
            'desc'=>$desc,
            'amt'=>$amt
        ]);

        if( strtolower($source) != 'deposit' ){
            $this->total_income += $amt;
        }

        $this->save();
    }

    public function debit_main_balance($amt, $source, $desc)
    {
        $this->main_balance -= $amt;
        TrxHistory::create([
            'user_id'=>$this->id, 
            'name'=>$source,
            'type'=>DEBIT,
            'desc'=>$desc,
            'amt'=>$amt
        ]);
        $this->save();
    }

    public function creditPV($amt)
    {
        // Validate inputs
        if ($amt <= 0) {
            return false;
        }

        // Get configuration values
        $cashReward = 1200; //cash
        $requiredPV = 750; //PV

        if ($cashReward <= 0 || $requiredPV <= 0) {
            return false;
        }

        // Use a transaction to ensure atomicity
        return DB::transaction(function () use ($amt, $cashReward, $requiredPV) {
            // Update total PV
            $this->pv += $amt;

            // Calculate new rewardable PV
            $newRewardablePv = $this->pv - $this->rewarded_pv;
            $newPvTimes = floor($newRewardablePv / $requiredPV);

            if ($newPvTimes > 0) {
                // Calculate and credit rewards
                $cashReward = $newPvTimes * $cashReward;
                $rewardedPV = $newPvTimes * $requiredPV;
                $this->rewarded_pv += $rewardedPV;

                $this->credit_main_balance($cashReward, 'PV Reward', "$rewardedPV PV Reward");
                $this->creditPVCommission([100, 50, 30, 20, 10], $newPvTimes, "PV Reward");
            }

            // Save updated values and trigger token rewards
            $this->save();
            $this->handleRewards('token', 3000, 5000, [500, 300, 200, 100]);
            return true;
        });
    }

    public function handleRewards($type, $requiredUnit, $cashReward, $commissionRewards)
    {
        $currentUnits = $this->$type; // e.g., token or coin
        $totalUnits = $this->{$type === 'token' ? 'pv' : 'token'}; // Related total units

        $rewardedUnits = $currentUnits * $requiredUnit;
        $newRewardableUnits = $totalUnits - $rewardedUnits;

        $newUnits = floor($newRewardableUnits / $requiredUnit);

        if ($newUnits <= 0) {
            return;
        }

        $this->$type += $newUnits;
        $this->save();

        $totalCashReward = $cashReward * $newUnits;
        $this->credit_main_balance($totalCashReward, ucfirst($type) . " Reward", "$newUnits $type Reward");
        // Credit commissions
        $this->creditPVCommission($commissionRewards, $newUnits, ucfirst($type) . " Reward");

        // Handle cascading rewards
        if ($type === 'token') {
            $this->handleRewards('coin', 10, 25000, [2000, 1000, 600, 200]);
        }
    }

    public function creditPVCommission($rewards, $times, $title, $step = 1)
    {
        // Validate inputs
        if (!is_array($rewards) || $times <= 0 || $step > 15) {
            return;
        }

        // Check for a referrer
        if ($this->ref_by) {
            $referrer = self::find($this->ref_by);

            if ($referrer) {
                $reward = $rewards[$step - 1] ?? end($rewards);
                $totalReward = $reward * $times;

                $referrer->credit_main_balance(
                    $totalReward,
                    $title,
                    getPositionWithSuffix($step) . " Gen. $title"
                );
                // Recursive call for next generation
                $referrer->creditPVCommission($rewards, $times, $title, $step + 1);
            }
        }
    }

    public function enter_gsteam_wheel()
    {
        $check = WheelGlobal::where('user_id', $this->id)->exists();
        if(!$check){
            WheelGlobal::create([
                'user_id'=>$this->id,
                'giving'=>1,
                'pending_balance'=>1500,
                'total_refs'=>$this->total_referrals
            ]);
        }
    }

    public function update_gsteam_wheel_referrals()
    {
        $wheel =  WheelGlobal::where('user_id', $this->id)->first();
        if($wheel){
            $wheel->total_refs = $this->total_referrals; 
            $wheel->save(); 
        }
    }

    public function save_total_income($amt)
    {
        $this->total_income += $amt;
        $this->save(); 
    }

}
