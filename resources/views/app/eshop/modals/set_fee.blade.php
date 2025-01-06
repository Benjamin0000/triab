<div class="modal fade" id="set_fee">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Set Fee</h5></div>
            <div class="modal-body">
                <form action="{{route('eshop.set_fee', $shop->id)}}" method="POST">
                    <div class="form-group">
                        <label for="">Vat (%)</label>
                        <input type="number" class="form-control" name="vat" step="any" value="{{$shop->vat}}" required>
                    </div>
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <div class="form-group">
                        <label for="">Service Charge (₦)</label>
                        <input type="number" class="form-control" name="service_fee" step="any" value="{{$shop->service_fee}}" required>
                    </div>
                     @csrf 
                    <div class="form-group">
                        <button class="btn btn-primary">Set Fee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>