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
    .rank_bal {
        background: linear-gradient(135deg, #F39C12, #D35400); /* Warm orange tones */
    }
    
    .total_referrals_bal {
        background: linear-gradient(135deg, #34495E, #2C3E50); /* Cool slate tones */
    }
</style>
<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <br>
                <h3 class="nk-block-title page-title">Triab Community</h3>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con main_bal">
                <i class="fas fa-wallet"></i>
                <div class="card-inner text-center">
                    <h6></h6>
                    <h6>Available Funds</h6>  
                </div>            
            </div>
        </div>



        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con rank_bal">
                <i class="fas fa-medal"></i>
                <div class="card-inner text-center">
                    <h6>Stars</h6>  
                    <div>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                        <i class="fa fa-star"></i>
                    </div>
                </div>            
            </div>
        </div>



        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con total_referrals_bal">
                <i class="fas fa-users"></i>
                <div class="card-inner text-center">
                    <h6></h6>
                    <h6>Your Team</h6>  
                </div>            
            </div>
        </div>

    </div>
</div>
@stop 