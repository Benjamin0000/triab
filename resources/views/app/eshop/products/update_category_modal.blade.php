<div class="modal fade" id="update_category{{$product->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Create Category</h5></div>
            <div class="modal-body text-start">
                <form class="update_category_form" enctype="multipart/form-data">
                    @if($parent)
                        <div class="alert alert-info">Parent Category: {{$parent->name}}</div>
                        <input type="hidden" name="parent_id" value="{{$parent_id}}">
                        <br>
                    @endif 
                    <div class="form-group">
                        <label class="form-label">Category Name</label>
                        <div class="form-control-wrap">
                            <input name="name" type="text" value="{{$product->name}}" class="form-control" required/>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label class="form-label">Category Logo</label>
                                    <div>
                                        <input type="file" name="logo" accept="image/jpeg, image/png, image/jpg, image/gif, image/webp">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5 text-end">
                                <img src="{{Storage::url($product->getPosterImage())}}" alt="" width="50">
                            </div>
                        </div>
                    </div>

                    @csrf 
                    <input type="hidden" name="id" value="{{$product->id}}">
                    <input type="hidden" name="type" value="0">
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <div class="msg"></div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>