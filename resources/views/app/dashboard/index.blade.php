@extends('app.layout')
@section('content')
<style>
    .bal_con {
        margin-bottom: 20px;
        min-height: 150px;
        justify-content: center;
        border-radius: 10px; /* Rounded edges for better aesthetics */
        position: relative;
    }

    .bal_con h6 {
        color: #ffffff;
        font-weight: bolder;
        font-size: 20px;
    }

    .bal_con i {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 30px;
        color: rgba(255, 255, 255, 0.7); /* Faint icon for aesthetics */
    }

    .main_bal {
        background: linear-gradient(135deg, #2C3E50, #4CA1AF); /* Dark slate to teal */
    }

    .reward_bal {
        background: linear-gradient(135deg, #3A6073, #16222A); /* Cool slate gray gradient */
    }

    .pv_bal {
        background: linear-gradient(135deg, #1F4037, #99F2C8); /* Dark forest green to soft mint */
    }

    .coin_bal {
        background: linear-gradient(135deg, #403B4A, #E7E9BB); /* Charcoal to muted gold */
    }

    .health_token_bal {
        background: linear-gradient(135deg, #8E44AD, #3498DB); /* Purple to light blue */
    }

    .rank_bal {
        background: linear-gradient(135deg, #F39C12, #D35400); /* Warm orange tones */
    }

    .mpp_bal {
        background: linear-gradient(135deg, #16A085, #2ECC71); /* Aqua to green */
    }

    .total_referrals_bal {
        background: linear-gradient(135deg, #34495E, #2C3E50); /* Cool slate tones */
    }
    .pkg_con{
        padding:10px; 
        border:1px solid #ccc; 
        margin-bottom: 10px; 
        border-radius: 10px; 
        min-height: 320px; 
        
    }
    .pkg_title {
        background: linear-gradient(135deg, #8E44AD, #3498DB); /* Gradient applied */
        -webkit-background-clip: text; /* Clips the gradient to the text */
        -webkit-text-fill-color: transparent; /* Makes the background visible through the text */
        text-align: center;
    }
    .services_name{
        font-size:15px; 
    }
</style>

@php 
    $user = Auth::user(); 
@endphp 

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
                <i class="fas fa-wallet"></i>
                <div class="card-inner text-center">
                    <h6>{{ format_with_cur($user->main_balance) }}</h6>
                    <h6>Main Balance</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con reward_bal">
                <i class="fas fa-gift"></i>
                <div class="card-inner text-center">
                    <h6>{{ format_with_cur($user->reward_balance) }}</h6>
                    <h6>Reward Balance</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con pv_bal">
                <i class="fas fa-chart-line"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->pv) }}</h6>
                    <h6>PV</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con coin_bal">
                <i class="fas fa-coins"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->token_balance, 5) }}</h6>
                    <h6>TRB Coin</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con health_token_bal">
                <i class="fas fa-heartbeat"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->health_token_balance, 2) }}</h6>
                    <h6>Health Token</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con rank_bal">
                <i class="fas fa-medal"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->rank) }}</h6>
                    <h6>Rank</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con mpp_bal">
                <i class="fas fa-trophy"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->mpp) }}</h6>
                    <h6 title="Monthly performance point">MPP</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con total_referrals_bal">
                <i class="fas fa-users"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->total_referrals) }}</h6>
                    <h6>Total Referrals</h6>  
                </div>            
            </div>
        </div>
    </div>

    <h5>
        Current Package: 
        <a href="#" id="up_pkg_btn" data-bs-toggle='modal' data-bs-target='#choose_package'>Upgrade</a>
    </h5>
    @include('app.includes.select_package_modal')
</div>

@if(!$user->package_id)
    <script>
        window.onload = ()=>{
            document.getElementById("up_pkg_btn").click();
        }
    </script>
@endif 
@stop
