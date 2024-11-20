@extends('app.layout')
@section('content')
<style>
.bal_con{
    margin-bottom: 20px;
    min-height: 150px;
    justify-content: center;
}

.bal_con h6{
    color:#ffffff;
    font-weight: bolder;
    font-size: 20px;
}

.main_bal {
    background: linear-gradient(135deg, #2C3E50, #4CA1AF); /* Dark slate to teal */
    color: #ffffff; /* Ensure text is readable */
}

.reward_bal {
    background: linear-gradient(135deg, #3A6073, #16222A); /* Cool slate gray gradient */
    color: #ffffff; /* Ensure text is readable */
}

.pv_bal {
    background: linear-gradient(135deg, #1F4037, #99F2C8); /* Dark forest green to soft mint */
    color: #ffffff; /* Ensure text is readable */
}

.coin_bal {
    background: linear-gradient(135deg, #403B4A, #E7E9BB); /* Charcoal to muted gold */
   color: #ffffff; /* Ensure text is readable */
}


</style>
@php 
    $user = Auth::user(); 
@endphp 
{{-- <div class="nk-block">
    <div class="row">
        <div class="col-xxl-12">
            <div class="card card-bordered">
                <div class="card-inner border-bottom">
                    <h6>Welcome?</h6>
                </div>            
            </div>
        </div>
    </div>
</div> --}}


<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Dashboard</h3>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con main_bal">
                <div class="card-inner text-center">
                    <h6>{{format_with_cur($user->main_balance)}}</h6>
                    <h6>Main Balance</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con reward_bal">
                <div class="card-inner text-center">
                    <h6>{{format_with_cur($user->main_balance)}}</h6>
                    <h6>Reward Balance</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con pv_bal">
                <div class="card-inner text-center">
                    <h6>{{number_format($user->pv)}}</h6>
                    <h6>PV</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con coin_bal">
                <div class="card-inner text-center">
                    <h6>{{number_format($user->token_balance, 5)}}</h6>
                    <h6>TRB COIN</h6>  
                </div>            
            </div>
        </div>


    </div>
</div>
@stop 