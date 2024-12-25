@extends('app.layout')
@section('content')
<div class="container">
    <br>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h5 class="text-center">Create Shop</h5>
            <form id="create_shop_form">
                <h6>Shop profile</h6>

                <div class="form-group">
                    <label for="">Name</label>
                    <input type="text" name="name" required placeholder="Shop name" class="form-control">
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Shop category</label>
                                <select required class="form-control" name="category">
                                    <option value="">Choose category</option>
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">Logo</label>
                                <div><input required type="file" name="logo" accept="image/jpeg, image/png, image/jpg, image/gif, image/webp"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Tell us about your shop</label>
                    <textarea name="description" required placeholder="Enter a short description about your shop"  class="form-control"></textarea>
                </div>
                @csrf 
                <h6>Shop location</h6>
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">State</label>
                                <select required id="state_select" name="state" class="form-control">
                                    <option value="">Select state</option>
                                    @foreach(config('states') as $state)
                                        <option value="{{$state['name']}}">{{$state['name']}}</option>
                                    @endforeach 
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">City</label>
                                <select required id="city_select" name="city" class="form-control">
                                    <option value="">Select a state first</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Address</label>
                    <input type="text" required placeholder="Enter shop address" class="form-control" name="address">
                </div>
                <p class="msg"></p>
                <div class="form-group">
                    <button class="btn btn-primary btn-block">Create</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    window.onload = ()=>{
        let states = @json(config('states')); 
        $(document).on('change', '#state_select', (e)=>{
            let selected_state = e.currentTarget.value;
            states.map((state)=>{
                let html_cities = ""; 
                if(state['name'] == selected_state){
                    html_cities = "<option value=''>Select city</option>"
                    let cities = state['cities'];
                    cities.map((city)=>{
                        html_cities+=`<option value='${city}'>${city}</option>`
                    })
                    $('#city_select').html(html_cities); 
                    return; 
                }

                if(selected_state == ""){
                    html_cities = "<option value=''>Select a state first</option>"; 
                    $('#city_select').html(html_cities); 
                }
            })
        }); 


        $(document).on('submit', '#create_shop_form',  (e)=>{
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
                url: '{{route('eshop.save')}}', // Replace with your server endpoint
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
                            window.location.href = '/e-shop'; 
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
@stop
