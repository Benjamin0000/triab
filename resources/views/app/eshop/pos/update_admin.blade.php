<div class="modal fade" id="update_admin{{$staff->id}}">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Update Staff</h5></div>
            <div class="modal-body">
                <form method="POST" class="update_staff_form text-start">
                    <div class="form-group">
                        <label for="">Full name</label>
                        <input type="text" name="name" required value="{{$staff->name}}" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option @selected($staff->admin) value="1">Admin</option>
                            <option @selected(!$staff->admin) value="0">Sales Rep.</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="{{$staff->id}}">
                    <input type="hidden" name="shop_id" value="{{$shop->id}}">
                    <div class="form-group">
                        <label for="">Pass code</label>
                        <input type="text" name="pass_code" class="form-control" value="{{$staff->pass_code}}" required>
                    </div>
                    @csrf 
                    <div class="msg"></div>
                    <div class="form-group">
                        <label for="">Suspend <input name="status" @checked(!$staff->status) value="1" type="checkbox"></label>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>