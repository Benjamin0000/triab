@extends('app.layout')
@section('content')
<style>
    .balance-card {
        margin-bottom: 20px;
        min-height: 150px;
        width: 100%;
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
    .stock-in { background: linear-gradient(135deg, #2C3E50, #4CA1AF); }
    .stock-out { background: linear-gradient(135deg, #3A6073, #16222A); }
</style>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-bordered h-100">
                <div class="card-inner border-bottom">
                    <p>
                        @if($product->parent_id)
                            <a href="{{route('eshop.products', [$product->shop_id, $product->parent_id])}}" class="btn btn-sm btn-primary"> <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                        @else
                            <a href="{{route('eshop.products', $product->shop_id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                        @endif 
                    </p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="header-section">
                                <h4 class="text-center">{{$product->shop->name}}</h4>
                            </div>
                            <h6 class="text-center">Stock History</h6>
                            <br>
                            <div class="text-center">
                                <div><img  width="100" src="{{Storage::url($product->getPosterImage())}}" alt=""></div>
                                <div>{{$product->name}}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-center">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="">
                                            <div>Stocks In</div>
                                            <h6 class="text-success">{{number_format($stock_in)}}</h6>
                                        </div>
                                    </div>
                            
                                    <div class="col-lg-4">
                                        <div class="">
                                            <div>Stocks Out</div>
                                            <h6 class="text-danger">{{number_format($stock_out)}}</h6>
                                        </div>
                                    </div>

                                    <div class="col-lg-4">
                                        <div class="">
                                            <div>Stocks Left</div>
                                            <h6>{{number_format($stocks_left)}}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div>
                                <form action="">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_data_picker">From Date:</label>
                                                <input type="text" name="from_date" id="start_data_picker" value="{{ $from_date }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="future_date_picker">To Date:</label>
                                                <input type="text" name="to_date" id="future_date_picker"  value="{{ $to_date }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <button class="btn btn-sm btn-primary btn-block">Search</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <br>
                    <div class="table-responsive">
                        <table class="table table-nowrap table-sm table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>Quantity</th>
                                    <th>Purpose</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($histories as $history)
                                    <tr class="text-center">
                                        <td>
                                            @if($history->type == CREDIT)
                                                <b class="text-success">+{{number_format($history->amt)}}</b>
                                            @else  
                                                <b class="text-danger">-{{number_format($history->amt)}}</b>
                                            @endif 
                                        </td>
                                        <td>
                                            {{$history->desc}}
                                        </td>
                                        <td>
                                            <div>{{$history->created_at->diffForHumans()}}</div> 
                                            {{$history->created_at->isoFormat('lll')}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{$histories->links()}}

                    @if(!$histories->first())
                        <br>
                        <div class="text-info text-center"><i class="fa fa-info-circle"></i> No records to show</div>
                    @endif 
                </div>
            </div> 
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('{{$from_date}}')
        flatpickr("#start_data_picker", {
            dateFormat: "Y-m-d",
            // minDate: "today", // Restrict past dates
        });

        flatpickr("#future_date_picker", {
            dateFormat: "Y-m-d",
            // minDate: "today", // Restrict past dates
        });
    });
</script>
@stop
