<div class="modal fade" id="add_stock{{$product->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Add {{$product->name}}</h5></div>
            <div class="modal-body text-start">
                <form action="{{route('eshop.product.add_stock', $product->id)}}" method="POST">
                    <div class="form-group">
                        <label for="">Quantity</label>
                        <input type="number" name="qty" autofocus value="" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <select name="reason" class="form-control" required>
                            <option value="">Select a reason</option>
                            <option value="New Stock">New Stock</option>
                            <option value="Stock Increase">Stock Increase</option>
                        </select>
                    </div>
                    @csrf 
                    <div class="form-group">
                        <button class="btn btn-sm btn-primary btn-block">Add Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>