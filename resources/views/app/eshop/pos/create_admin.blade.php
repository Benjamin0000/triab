<div class="modal fade" id="create_admin">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content ">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">Create Staff</h5></div>
            <div class="modal-body">
                <form action="" method="POST" id="create_staff_form">
                    <div class="form-group">
                        <label for="">Full name</label>
                        <input type="text" name="name" required class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="1">Admin</option>
                            <option value="0">Sales Rep.</option>
                        </select>
                    </div>
                    <input type="hidden" name="id" value="{{$shop->id}}">
                    <div class="form-group">
                        <label for="">Pass code</label>
                        <input type="text" name="pass_code" class="form-control" required>
                    </div>
                    @csrf 
                    <div class="msg"></div>
                    <div class="form-group">
                        <button class="btn btn-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    window.onload = ()=>{
        $(document).on('submit', '#create_staff_form',  (e)=>{
            e.preventDefault();
            var form = $(e.currentTarget);
            var msg = form.find('.msg'); 
            var btn = form.find('button'); 
            var btn_content = btn.text(); 
            msg.html(''); 
            loadButton(btn)
            var formData = new FormData(form[0]); // Create FormData object with form elements
            $.ajax({
                type: 'POST',
                url: '{{route('eshop.create_staff')}}', // Replace with your server endpoint
                data: formData,
                contentType: false, // Required for file upload
                processData: false, // Don't process the files
                success: function (res) {
                    unLoadButton(btn, btn_content)
                    if(res.error){
                        msg.html('<p class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> '+res.error+'</p>');
                    }else if(res.success){
                        msg.html('<p class="alert alert-success"><i class="fa-solid fa-circle-check"></i> '+res.success+'</p>');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function (xhr, status, error) {
                    unLoadButton(btn, btn_content);
                    if (xhr.status === 0) {
                        msg.html("<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Network error: Please check your internet connection.</div>");
                        // This indicates the error is likely caused by no internet connection
                    } else {
                        msg.html("<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Something went wrong please try again</div>");
                    }
                }
            });
        })


        $(document).on('submit', '.update_staff_form',  (e)=>{
            e.preventDefault();
            var form = $(e.currentTarget);
            var msg = form.find('.msg'); 
            var btn = form.find('button'); 
            var btn_content = btn.text(); 
            msg.html(''); 
            loadButton(btn)
            var formData = new FormData(form[0]); // Create FormData object with form elements
            $.ajax({
                type: 'POST',
                url: '{{route('eshop.update_staff')}}', // Replace with your server endpoint
                data: formData,
                contentType: false, // Required for file upload
                processData: false, // Don't process the files
                success: function (res) {
                    unLoadButton(btn, btn_content)
                    if(res.error){
                        msg.html('<p class="alert alert-danger"><i class="fa-solid fa-circle-info"></i> '+res.error+'</p>');
                    }else if(res.success){
                        msg.html('<p class="alert alert-success"><i class="fa-solid fa-circle-check"></i> '+res.success+'</p>');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    }
                },
                error: function (xhr, status, error) {
                    unLoadButton(btn, btn_content);
                    if (xhr.status === 0) {
                        msg.html("<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Network error: Please check your internet connection.</div>");
                        // This indicates the error is likely caused by no internet connection
                    } else {
                        msg.html("<div class='alert alert-danger'><i class='fa-solid fa-circle-info'></i> Something went wrong please try again</div>");
                    }
                }
            });
        })
    }
</script>