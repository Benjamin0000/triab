@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Reward Settings</h4>
            </div>
        </div>
    </div>
    <br>

    <form method="POST" action="{{route('admin.reward.settings')}}">
        <h6>PV Reward</h6>
        <div class="row">
            <div class="col-md-2">
                <label for="">Every PV</label>
                <input type="number" value="{{get_register('pv_to_cash')}}" step="any" name="pv_to_cash" class="form-control" placeholder="1500">
            </div>
            <div class="col-md-1">
                <br>
                <div class="text-center">
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </div>
            </div>
            <div class="col-md-2">
                <label for="">Token </label>
                <input readonly value="1" type="number" step="any" name="pv_to_token" class="form-control" placeholder="1500">
            </div>
            <div class="col-md-1">
                <br>
                <div class="text-center">
                    <i class="fa-solid fa-circle-arrow-right"></i>
                </div>
            </div>
            <div class="col-md-2">
                <label for="">Reward ({{currency_symbol()}})</label>
                <input value="{{get_register('pv_cash')}}" type="text" name="pv_cash" class="form-control" placeholder="2500">
            </div>
        </div>
        <br>
        <div>
            <div class="row">
                <div class="col-md-2">
                    <label for="">PV to Health Token</label>
                    <input value="{{get_register('pv_to_health')}}" type="number" step="any" name="pv_to_health" class="form-control" placeholder="1000">
                </div>
                <div class="col-md-2">
                    <label for="">Token to Coin </label>
                    <input value="{{get_register('token_to_coin')}}" type="number" step="any" name="token_to_coin" class="form-control" placeholder="10">
                </div>
                <div class="col-md-3">
                    <label for="">Every One Coin Reward ({{currency_symbol()}}) </label>
                    <input value="{{get_register('coin_reward')}}" type="number" step="any" name="coin_reward" class="form-control" placeholder="25000">
                </div>
            </div>
        </div>

        @csrf 
      
        <div class="col-md-2">
            <br>
            <button class="btn btn-primary btn-block">UPDATE</button>
        </div>
    </form>
</div>


@stop 