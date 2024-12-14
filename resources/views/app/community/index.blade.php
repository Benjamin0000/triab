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
    .pv_bal{
        background: linear-gradient(135deg, #1F4037, #99F2C8); /* Dark forest green to soft mint */
    }
    .rank_bal {
        background: linear-gradient(135deg, #6d4403, #D35400); /* Warm orange tones */
    }
    .total_referrals_bal {
        background: linear-gradient(135deg, #34495E, #2C3E50); /* Cool slate tones */
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
    .active_level{
        color:white !important; 
        background: #1F4037 !important;
    }
    .blinking_status{
        animation: blink-animation 1.1s steps(5, start) infinite; /* Blink animation */
    }
    /* Keyframes for Blinking Effect */
    @keyframes blink-animation {
        50% {
            opacity: 0; /* Invisible at 50% of the animation cycle */
        }
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
                    <h6>{{format_with_cur($available_funds)}}</h6>
                    <h6>Available Funds</h6>  
                    <form action="{{route('community.move_to_main')}}" method="POST">
                        @csrf
                        <button class="btn btn-info btn-sm">Move to main balance</button>
                    </form>
                </div>            
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con pv_bal">
                <i class="fas fa-wallet"></i>
                <div class="card-inner text-center">
                    <h6>{{format_with_cur($total_received)}}</h6>
                    <h6>Total Received</h6>  
                </div>            
            </div>
        </div>



        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con rank_bal">
                <div><i class="fas fa-medal"></i></div>
                <div class="card-inner text-center">
                    <div>
                        @for($i = 1; $i <= 3; $i++)
                            <em class="fas fa-star @if($stage > $i) active_rank_color @else default_rank_color @endif "></em>
                        @endfor
                    </div>
                    <h6>Stars</h6>  
                </div>            
            </div>
        </div>



        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered bal_con total_referrals_bal">
                <i class="fas fa-users"></i>
                <div class="card-inner text-center">
                    <h6>{{number_format($total_referrals)}}</h6>
                    <h6>Your Downlines</h6>  
                </div>            
            </div>
        </div>

    </div>
 
    <h4>Stages</h4>
    <div class="row">

        @for($i = 1; $i <= 3; $i++)
            @switch($i)
                @case(1)
                    @php $levels = wheel_one @endphp
                    @break
                @case(2)
                    @php $levels = wheel_two @endphp
                    @break
                @default
                    @php $levels = wheel_three @endphp
            @endswitch
            <div class="col-md-4">
                <div style="background: #fff;"> 
                    <br>
                    <h5 class="text-center">Stage {{$i}}</h5>
                    <br>
                    <div class="table-responsive table-nowrap">
                        <table class="table table-sm">
                            <thead>
                                <tr class="text-center">
                                    <th>Level</th>
                                    <th>Income</th>
                                    <th>Downlines</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 0; @endphp 
                                @foreach ($levels as $level)
                                    @php $no++; @endphp 
                                    <tr class="text-center">
                                        <td style="vertical-align: middle;" class="@if($stage == $i && $no == $Level ) active_level @endif"><b>{{$no}}</b></td>
                                        <td style="vertical-align: middle;" class="@if($stage == $i && $no == $Level ) active_level @endif">
                                            {{format_with_cur($level['amt'], 0)}} x {{$level['times']}}
                                            <div class="text-center"><small>Received</small></div>
                                            <div>
                                                {{format_with_cur($level['amt'], 0)}} x 
                                                <b class="text-info">
                                                    @if($stage > $i || ($stage == $i && $Level > $no) )
                                                        {{$level['times']}}
                                                    @elseif($stage == $i && $no == $Level)
                                                        {{$times}}
                                                    @else 
                                                        0
                                                    @endif 
                                                </b>
                                            </div>
                                        </td>
                                        <td style="vertical-align: middle;" class="@if($stage == $i && $no == $Level ) active_level @endif">
                                            <b title="Your Team Members" class="text-success">{{$total_referrals}}</b> of
                                            <span class="text-danger" title="Required Team Members">{{$level['total_refs']}}</span> 
                                        </td>
                                        <td style="vertical-align: middle;" class="@if($stage == $i && $no == $Level ) active_level @endif">
                                            @if($stage > $i || ($stage == $i && $Level > $no) )
                                                <em class="text-success fas fa-check-circle"></em>
                                            @elseif($stage == $i && $no == $Level)    
                                                <em class="blinking_status text-warning fas fa-dot-circle"></em>
                                            @else 
                                                <em class="fas fa-dot-circle"></em>
                                            @endif 
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endfor

    </div>
</div>
@stop 