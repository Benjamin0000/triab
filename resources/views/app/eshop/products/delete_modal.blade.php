<div class="modal fade" id="delete_product_modal{{$product->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Delete {{$product->name}}</h5></div>
            <div class="modal-body">
                <br>
                @if($product->type == CATEGORY)
                    <div class="text-center">Your are about to delete the {{$product->name}} category</div>
                @else  
                    <div class="text-center">Your are about to delete {{$product->name}}</div>
                @endif 

                <div class="text-center">Are you sure you want to continue?</div>
                <form method="POST" action="{{route('eshop.product.delete_product', $product->id)}}">
                   @csrf 
                   @method('delete')
                   <br>
                    <div class="form-group text-center">
                        <button class="btn btn-danger btn-block">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>