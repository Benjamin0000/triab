@extends('app.layout')
@section('content')
<style>
    .item_card{
        background: #fff; 
        margin-bottom:10px; 
        padding:10px;
    }
    .item_card h6{
        font-size:20px; 
        margin-top:10px; 
    }
    .item_card:hover{
        border: 1px solid blue;
    }
</style>

<br>
<div class="container-fluid">
    <h4>Triab Market</h4>
    <br>
    <div>What are you looking for?</div>
    <div class="row">
        @foreach($categories as $category)
            <div class="col-md-3">
                <a href="{{route('triab_market.show_shops', $category->id)}}" class="item_link">
                    <div class="text-center item_card">
                        <div>
                            <img src="{{Storage::url($category->icon)}}" width="100" alt="" class="img-fluid">
                        </div>
                        <h6>{{$category->name}}</h6>
                    </div>
                </a>
            </div>
        @endforeach 
    </div>
</div>

@stop 