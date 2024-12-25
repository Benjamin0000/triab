<div>
    <button class="btn btn-primary btn-sm" data-bs-target="#create_category" data-bs-toggle="modal">Add Category</button>
    @if($parent_id)
        <a href="{{route('eshop.add_product', [$shop->id, $parent->id])}}" class="btn btn-primary btn-sm">Add {{$parent->name}}</a>
    @endif 
</div>
<br>
@include('app.eshop.products.create_category_modal')
