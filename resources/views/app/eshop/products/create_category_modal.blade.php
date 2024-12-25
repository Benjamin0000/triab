<div class="modal fade" id="create_category">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Create Category</h5></div>
            <div class="modal-body">
                <form id="create_category_form" enctype="multipart/form-data">
                    @if($parent)
                        <div class="alert alert-info">Parent Category: {{$parent->name}}</div>
                        <input type="hidden" name="parent_id" value="{{$parent_id}}">
                        <br>
                    @endif 
                    <div class="form-group">
                        <label class="form-label">Category Name</label>
                        <div class="form-control-wrap">
                            <input name="name" type="text" class="form-control" value="" required/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category Logo</label>
                        <div>
                            <input type="file" name="logo" required accept="image/jpeg, image/png, image/jpg, image/gif, image/webp">
                        </div>
                    </div>
                    @csrf 
                    <input type="hidden" name="type" value="0">
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <div class="msg"></div>
                    <div class="form-group">
                        <button class="btn btn-primary btn-block">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>