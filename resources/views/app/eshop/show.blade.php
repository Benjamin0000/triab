@extends('app.layout')
@section('content')
<style>
    .balance-card {
        margin-bottom: 20px;
        min-height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        position: relative;
        color: #ffffff;
        text-align: center;
    }
    
    .balance-card h6 {
        font-weight: bold;
        font-size: 18px;
        margin: 5px 0;
        color:white; 
    }

    .balance-card i {
        position: absolute;
        top: 10px;
        left: 10px;
        font-size: 30px;
        color: rgba(255, 255, 255, 0.7);
    }

    .sales-today { background: linear-gradient(135deg, #2C3E50, #4CA1AF); }
    .total-sales { background: linear-gradient(135deg, #3A6073, #16222A); }
    .profit-today { background: linear-gradient(135deg, #1F4037, #99F2C8); }
    .total-profit { background: linear-gradient(135deg, #403B4A, #E7E9BB); }
    .total-products { background: linear-gradient(135deg, #8E44AD, #3498DB); }
    .total-reward { background: linear-gradient(135deg, #16A085, #2ECC71); }
    .todays-reward { background: linear-gradient(135deg, #d48c19, #3498DB); }

    .section-header {
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0 10px;
    }
</style>

<div class="container-fluid">
    <div class="header-section">
        <h4 class="page-title">{{$shop->name}}</h4>
        <div>{{$shop->storeID}}</div>
        <a href="{{route('eshop.products', $shop->id)}}" class="btn btn-sm btn-primary mt-2">Products</a>
        <a href="#" class="btn btn-sm btn-primary mt-2">Sales Report</a>
        <a href="#" class="btn btn-sm btn-primary mt-2">POS</a>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card sales-today">
                <i class="fas fa-wallet"></i>
                <h6>Today's Sales</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card total-sales">
                <i class="fas fa-chart-bar"></i>
                <h6>Total Sales</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card profit-today">
                <i class="fas fa-chart-line"></i>
                <h6>Today's Profit</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card total-profit">
                <i class="fas fa-money-bill-wave"></i>
                <h6>Total Profit</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card total-products">
                <i class="fas fa-box"></i>
                <h6>Total Products</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card todays-reward">
                <i class="fas fa-award"></i>
                <h6>Today's Reward</h6>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card card-bordered balance-card total-reward">
                <i class="fas fa-gift"></i>
                <h6>Total Reward</h6>
            </div>
        </div>


    </div>

    <div class="section-header">Sales</div>
</div>
@stop
