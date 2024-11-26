<?php
namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
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

    public function credit_pv($amt)
    {
        // Get configuration values
        $pv_cash = (float) get_register('pv_cash'); // Cash reward for one token
        $pv_to_cash = (int) get_register('pv_to_cash'); // PV required to earn one token and cash
        $pv_to_health = (int) get_register('pv_to_health'); // PV required to earn one health token
        $token_to_coin = (int) get_register('token_to_coin'); // Tokens required to convert to one coin
        $coin_reward = (float) get_register('coin_reward'); // Cash reward for one coin

        // Update total PV
        $this->pv += $amt;
        $this->save(); // Save PV increment immediately

        // Reward tokens and main balance based on PV
        $new_rewardable_pv = $this->pv - ($this->rewarded_token * $pv_to_cash);
        $new_tokens = floor($new_rewardable_pv / $pv_to_cash); // New tokens to reward

        if ($new_tokens > 0) {
            $cash_reward = $new_tokens * $pv_cash;
            $this->credit_main_balance($cash_reward, 'token', "$new_tokens Token Reward");
            $this->rewarded_token += $new_tokens;
            $this->token += $new_tokens;
        }

        // Reward health tokens based on PV
        $new_health_pv = $this->pv - ($this->rewarded_health_token * $pv_to_health);
        $new_health_tokens = floor($new_health_pv / $pv_to_health);

        if ($new_health_tokens > 0) {
            $this->health_token += $new_health_tokens;
            $this->rewarded_health_token += $new_health_tokens;
        }

        // Reward coins and main balance based on tokens
        $new_rewardable_tokens = $this->token - $this->rewarded_coin * $token_to_coin;
        $new_coins = floor($new_rewardable_tokens / $token_to_coin);

        if ($new_coins > 0) {
            $coin_cash_reward = $new_coins * $coin_reward;
            $this->credit_main_balance($coin_cash_reward, 'coin', "$new_coins Coin Reward");
            $this->rewarded_coin += $new_coins;
            $this->coin += $new_coins;
        }

        // Save updated values
        $this->save();
    }

}
