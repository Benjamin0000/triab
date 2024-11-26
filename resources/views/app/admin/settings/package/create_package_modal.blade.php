<div class="modal fade" id="create_package">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">New Package</h5></div>
            <div class="modal-body">
                <form class="create_package_form">
                    <div class="form-group">
                        <label class="form-label">Package Name</label>
                        <div class="form-control-wrap">
                            <input name="name" type="text" class="form-control" value="" required/>
                        </div>
                    </div>
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Cost</label>
                        <div class="form-control-wrap">
                            <input name="cost" type="number" step="any" class="form-control" value="" required/>                        
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Cashback</label>
                        <div class="form-control-wrap">
                            <input name="cashback" type="number" step="any" class="form-control" value="" required/>                        
                        </div>
                    </div>
                    
                    

                    <div class="form-group">
                        <label class="form-label">Discount</label>
                        <div class="form-control-wrap">
                            <input name="discount" type="number" step="any" class="form-control" required/>                        
                        </div>
                    </div>
                   
                    
                    <div class="form-group">
                        <label class="form-label">Earning Level</label>
                        <div class="form-control-wrap">
                            <input name="level" type="number" class="form-control" required/>                        
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Select Services</label>
                        <p>
                            @foreach($all_services as $name=>$key)
                                <label class="btn" style="margin-bottom:10px;background:#eee">
                                    {{make_readable($name)}} &nbsp;
                                    <input type="checkbox" name="services[]" value="{{$key}}">
                                </label>
                            @endforeach
                        </p> 
                    </div>
                   


                    <div class="msg"></div>
                    <div class="form-group">
                        <button src="{{route('admin.packages.create_package')}}" class="btn btn-primary btn-block">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>