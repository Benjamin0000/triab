@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="header-section">
        <h4 class="page-title">{{$shop->name}} | Products</h4>
        <div>{{$shop->storeID}}</div>
    </div>
    
    @foreach ($titles as $title) 
        <span> Products <i class="fa fa-arrow-right"></i> {{$title}}</span>
    @endforeach
    @include('app.eshop.products.add_product_button')

    <div class="card card-bordered h-100">
        <div class="card-inner border-bottom">
            <p>
                @if($parent && $parent->parent_id)
                    <a href="{{route('eshop.products', [$shop->id, $parent->parent_id])}}" class="btn btn-sm btn-primary"> <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                @elseif($parent && !$parent->parent_id)
                    <a href="{{route('eshop.products', $shop->id)}}" class="btn btn-sm btn-primary"> <i class="fa fa-arrow-left"></i> &nbsp;Go Back</a>
                @endif 
            </p>
            <div class="table-responsive">
                <table class="table table-nowrap">
                    <thead>
                        <tr class="text-center">
                            <th></th>
                            <th>Name</th>
                            <th>Other</th>
                            <th>Created</th>
                            <th>Actions</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = tableNumber(20) @endphp 
                        @foreach($products as $product)
                            <tr class="text-center">
                                <td>
                                    <img width="50" src="{{Storage::url($product->getPosterImage())}}" alt="">
                                </td>
                                <td>
                                    {{$product->name}}
                                </td>
                                <td>
                                    @if($product->type == PRODUCT)
                                        <div>Cost Price : {{format_with_cur($product->cost_price)}}</div>
                                        <div>Selling Price: {{format_with_cur($product->selling_price)}}</div>
                                    @else 
                                        Category
                                    @endif 
                                </td>
                                <td>
                                    {{$product->created_at->isoFormat('lll')}}
                                    <div>{{$product->created_at->diffForHumans()}}</div> 
                                </td>
                                <td>
                                    @if($product->type == CATEGORY)
                                        <a title="View Products" href="{{route('eshop.products', [$shop->id, $product->id])}}" style="padding-top:0px;padding-bottom:0px" class="btn btn-sm btn-outline-info"><i class="fa fa-eye"></i> &nbsp;({{$product->total_childeren()}})</a>
                                    @endif

                                    @if($product->type == CATEGORY)
                                        <a title="Edit Product" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#update_category{{$product->id}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    @else 
                                        <a title="Edit Product" href="{{route('eshop.show_edit_product', $product->id)}}" class="btn btn-outline-primary btn-sm"><i class="fa fa-edit"></i></a>
                                    @endif 
                                    
                                    <a title="Delete Product" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#delete_product_modal{{$product->id}}" class="btn btn-outline-danger btn-sm"><i class="fa fa-trash"></i></a>
                                    
                                    @include('app.eshop.products.delete_modal')
                                    @include('app.eshop.products.update_category_modal')
                                </td>
                                <td>
                                    @if($product->type == PRODUCT)
                                        <b>{{number_format($product->total)}}</b>
                                        <div>
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#add_stock{{$product->id}}" class="btn btn-primary btn-sm"><i class="fa-solid fa-plus"></i></a>
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#remove_stock{{$product->id}}" class="btn btn-danger btn-sm"><i class="fa-solid fa-minus"></i></a>
                                        </div>

                                        <a href="{{route('eshop.stock.history', $product->id)}}">History</a>

                                        @include('app.eshop.products.stock.add_modal')
                                        @include('app.eshop.products.stock.remove_modal')
                                    @else 
                                        --
                                    @endif 
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{$products->links()}}
        </div>
    </div> 
</div>

@include('app.eshop.products.scripts')
@stop
