<div class="modal fade" id="update_category{{$category->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Update Category</h5></div>
            <div class="modal-body text-start">
                <form method="POST" action="{{route('admin.update_eshop_category', $category->id)}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" value="{{$category->name}}" class="form-control">
                    </div>
                    @method('put')
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div><label for="">Logo</label></div>
                                    <input type="file" name="logo">
                                </div>
                            </div>
                            <div class="col-md-6 text-end">
                                <img src="{{Storage::url($category->icon)}}" width="50" alt="">
                            </div>
                        </div>
                    </div>

                    @csrf 
                    <div class="form-group">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>