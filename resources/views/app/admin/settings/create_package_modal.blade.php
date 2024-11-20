<div class="modal fade" id="create_package">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            <div class="modal-header"><h5 class="modal-title">New Package</h5></div>
            <div class="modal-body">
                <form id="create_package_form">
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
    $(document).on('submit', ".create_package_form", function(e){
        e.preventDefault(); // Prevent the default form submission
        var form = $(e.currentTarget);
        var msg = form.find('.msg')
        var btn = form.find('button')
        var btn_content = btn.text(); 
        msg.html(''); 
        loadButton(btn)
        var formData = new FormData(form[0]); // Create FormData object with form elements
        $.ajax({
            type: 'POST',
            url: '{{route('admin.packages.create_package')}}', // Replace with your server endpoint
            data: formData,
            contentType: false, // Required for file upload
            processData: false, // Don't process the files
            success: function (res) {
                unLoadButton(btn, btn_content)
                if(res.error){
                    msg.html('<p class="alert alert-danger">&#9432; '+res.error+'</p>');
                }else if(res.success){
                    msg.html('<p class="alert alert-success">'+res.success+'</p>');
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