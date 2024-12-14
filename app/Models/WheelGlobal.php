<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class WheelGlobal extends Model
{
    use Uuids;

    protected $fillable = [
        'user_id',
        'giving',
        'default',
        'pending_balance', 
        'total_refs'
    ];   

    public function user()
    {
        return $this->belongsTo(User::class); 
    }

    public function credit_sponsor_commission($amt)
    {
        $pct = 10; //sponsors percentage
        $user = User::find($this->user_id);
        if($user && $user->ref_by){
            $sponsor = User::find($user->ref_by);
            if($sponsor){
                $com = calculate_pct($amt, $pct);
                $sponsor->credit_main_balance(
                    $com,
                    'Triab Community',
                    "Triab Community Commission"
                ); 
            }
        }
    }
}