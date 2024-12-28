<div class="modal fade" id="remove_stock{{$product->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Subtract {{$product->name}}</h5></div>
            <div class="modal-body text-start">
                <form action="{{route('eshop.product.remove_stock', $product->id)}}" method="POST" action="">
                    <div class="form-group">
                        <label for="">Quantity</label>
                        <input type="number" name="qty" autofocus='true' class="form-control" placeholder="0" required>
                    </div>
                    <div class="form-group">
                        <select name="reason" class="form-control" required>
                            <option value="">Select a reason</option>
                            <option value="Damage">Damage</option>
                            <option value="For Use">For Use</option>
                            <option value="Excess">Excess</option>
                        </select>
                    </div>
                    @csrf 
                    <div class="form-group">
                        <button class="btn btn-danger btn-block btn-sm">Subtract Stock</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>