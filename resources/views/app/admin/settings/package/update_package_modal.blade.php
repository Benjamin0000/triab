<div class="modal fade" id="update_package{{$package->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Update Package</h5></div>
            <div class="modal-body">
                <form class="create_package_form text-start">
                    <div class="form-group">
                        <label class="form-label">Package Name</label>
                        <div class="form-control-wrap">
                            <input name="name" type="text" class="form-control" value="{{$package->name}}" required/>
                        </div>
                    </div>
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label class="form-label">Cost</label>
                        <div class="form-control-wrap">
                            <input name="cost" type="number" step="any" class="form-control" value="{{$package->cost}}" required/>                        
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cashback</label>
                        <div class="form-control-wrap">
                            <input name="cashback" type="number" step="any" class="form-control" value="{{$package->cashback}}" required/>                        
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Discount</label>
                        <div class="form-control-wrap">
                            <input name="discount" type="number" step="any" class="form-control" value="{{$package->discount}}" required/>                        
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Earning Level</label>
                        <div class="form-control-wrap">
                            <input name="level" type="number" class="form-control" value="{{$package->max_gen}}" required/>                        
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Select Services</label>
                        <p style="max-width: 100%; overflow-wrap: break-word; word-wrap: break-word; white-space: normal;">
                            @foreach($all_services as $name => $key)
                                <label class="btn" style="margin-bottom:10px; background:#eee;">
                                    {{ make_readable($name) }} &nbsp;
                                    <input type="checkbox" @checked(in_array($key, $package->services)) name="services[]" value="{{ $key }}">
                                </label>
                            @endforeach
                        </p>
                    </div>
                    
                   

                    <div class="msg"></div>
                    <div class="form-group">
                        <button src="{{route('admin.package.update', $package->id)}}" class="btn btn-primary btn-block">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>