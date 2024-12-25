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
        background: linear-gradient(135deg, #d48c19, #3498DB); /* Warm orange tones */
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
        position: relative;
        /* overflow: hidden; */
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
    .select_pkg_btn_con{
        position: absolute;
        bottom: 10px; 
        left: 0;
        width: 100%;
        padding-left:10px; 
        padding-right:10px;
    }
    .active_rank_color{
        background: linear-gradient(45deg, #FFD700, #FF8C00, #FFA500);
    }
    .default_rank_color{
        background: #272626; 
    }
    .rank_bal em{
        font-size: 25px;
        display: inline-block;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
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
            <div class="card card-bordered bal_con pv_bal">
                <i class="fas fa-chart-line"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->pv) }}</h6>
                    <h6>PV</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con reward_bal">
                <i class="fa-regular fa-gem"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->token) }}</h6>
                    <h6>Token</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con coin_bal">
                <i class="fas fa-coins"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->coin, 5) }}</h6>
                    <h6>TRB Coin</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con health_token_bal">
                <i class="fas fa-heartbeat"></i>
                <div class="card-inner text-center">
                    <h6>{{ number_format($user->health_token_balance) }}</h6>
                    <h6>Health Card</h6>  
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con rank_bal">
                <i class="fas fa-medal"></i>
                <div class="card-inner text-center">
                    <h6>
                        <em class='fas fa-star  @if($user->coin >= 3)  active_rank_color @else default_rank_color @endif'></em> 
                        <em class='fas fa-star  @if($user->coin >= 10) active_rank_color @else default_rank_color @endif'></em> 
                        <em class='fas fa-star  @if($user->coin >= 20) active_rank_color @else default_rank_color @endif'></em> 
                        <em class='fas fa-star  @if($user->coin >= 25) active_rank_color @else default_rank_color @endif'></em> 
                        <em class='fas fa-star  @if($user->coin >= 50) active_rank_color @else default_rank_color @endif'></em> 
                    </h6>
                    <h6>Rank</h6>  
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

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con mpp_bal">
                <i class="fas fa-wallet"></i>
                <div class="card-inner text-center">
                    <h6>{{ format_with_cur($user->total_income) }}</h6>
                    <h6 title="Total Income">Total Income</h6>  
                </div>            
            </div>
        </div>
    </div>

    <h6>
        Current Package: 
        <a href="#" id="up_pkg_btn" data-bs-toggle='modal' data-bs-target='#choose_package'>UPGRADE</a>
    </h6>
    @include('app.includes.select_package_modal')
    <br>
    <h6>Transaction History</h6>
    @include('app.dashboard.trx_history_table')
</div>


<script>
window.onload = ()=>{

    @if(!$user->package_id)
        document.getElementById("up_pkg_btn").click();
    @endif


    

    $(document).on("click", '#trx_pg_links_con .page-link', function(e){
        e.preventDefault(); 
        let link = $(e.currentTarget).attr('href');
        let page = link.split('=')[1]; 
        let route = '{{route('dashboard.trx_history')}}'
  
        $.ajax({
            type: 'get',
            url: route+'?page='+page,
            success: function (res) {
                let view = res.view; 
                $("#trx_table_con").html(view); 
            }
        });
    })

    $(document).on("submit", '.package_selector', function(e){
        e.preventDefault();
        let form = $(e.currentTarget)
        let btn = form.find('button')

        let btn_content = btn.text(); 
        let url = '{{route('dashboard.select_package')}}'; 
        loadButton(btn)

        $.ajax({
            type: 'POST',
            url: url, 
            data: form.serialize(),
            success: function (res) {
                unLoadButton(btn, btn_content)
                if(res.error){
                    $(btn).notify(res.error, { position:"top center", className:'error' });
                }else if(res.success){
                    $(btn).notify(res.success, { position:"top center", className:'success' });
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 1000);
                }
            },
            error: function (xhr, status, error) {
                unLoadButton(btn, btn_content);
                if (xhr.status === 0) {
                    $(btn).notify('Network error: Please check your internet connection.', { position:"top center", className:'error' });
                } else {
                    $(btn).notify('Something went wrong please try again', { position:"top center", className:'error' });
                }
            }
        });
    }); 
}
</script>
@stop
