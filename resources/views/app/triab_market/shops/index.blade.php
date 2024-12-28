@extends('app.layout')
@section('content')

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