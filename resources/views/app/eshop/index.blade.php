@extends('app.layout')
@section('content')
<style>
    .shop-image{
        height: 150px;
        overflow: hidden;
    }

    .shop-image img{
        width: 100%;
        height: auto;
        display: block; 
        object-fit: cover;
    }
    .btn_act_con{
        padding-left:12px;
        padding-right:12px; 
    }
    .btn-act{
        border-radius: 0; 
    }
</style>
<div>
    <a href="{{route('eshop.create')}}" class="btn btn-primary">New Shop</a>
</div>
 
<br>
<h5>Your shops</h5>

<div class="row">
    @foreach($shops as $shop)
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center">
                    {{truncateText($shop->name, 30)}}
                </div>
                <div class="card-body p-0">
                   <div class="shop-image">
                        <img src="{{Storage::url($shop->logo)}}" alt="">
                   </div>
                   <div class="p-2">
                        <div><b>ID:{{$shop->storeID}}</b></div>

                        <i class="fa-solid fa-landmark text-primary"></i>
                        <span>{{$shop->state}} <i class="fa-solid fa-arrow-right"></i> {{$shop->city}}</span>

                        <div><i class="fa-solid fa-location-dot text-danger"></i>  {{truncateText($shop->address, 50)}}</div>
                   </div>
                   <div class="p-2">
                        {{truncateText($shop->description, 100)}}
                   </div>
                   <div class="btn_act_con">
                        <div class="row">
                            <div class="col-4 p-0">
                                <a href="{{route('eshop.dashboard', $shop->id)}}" class="btn btn-primary btn-block btn-act" title="Manage"><i class="fas fa-eye"></i></a>
                            </div>
                            <div class="col-4 p-0">
                                <a href="{{route('eshop.edit', $shop->id)}}" title="Edit" class="btn btn-info btn-block btn-act"><i class="fas fa-edit"></i></a>
                            </div>
                            <div class="col-4 p-0">
                                <form action="{{route('eshop.delete', $shop->id)}}" method="POST">
                                    @csrf 
                                    @method('delete')
                                    <button onclick="return confirm('Are you sure about this?')" class="btn btn-danger btn-block btn-act" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </div>
                   </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

@stop