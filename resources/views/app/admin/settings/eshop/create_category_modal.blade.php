<div class="modal fade" id="create_category">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Create Category</h5></div>
            <div class="modal-body">
                <form method="POST" action="{{route('admin.create_eshop_category')}}" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <div><label for="">Logo</label></div>
                        <input type="file" required name="logo">
                    </div>
                    @csrf 
                    <div class="form-group">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>