@extends('app.layout')
@section('content')
@php $all_services = all_services(); @endphp 
<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Package Settings</h4>
            </div>
        </div>
    </div>

    @include('app.admin.settings.package.create_package_modal')

    <form class="row" method="POST" action="{{route('admin.package.update_parameters')}}">
        <div class="col-md-3">
            <label for="">Ref. Commission (%)</label>
            <input value="{{get_register('cashback')}}" type="text" name="cashback" class="form-control" placeholder="eg 1,2,4,5,5">
        </div>
        @csrf 
        <div class="col-md-3">
            <label for="">PV</label>
            <input value="{{get_register('pv')}}" type="text" name="pv" class="form-control" placeholder="eg 1,2,4,5,5">
        </div>
        <div class="col-md-2">
            <label for="">PV Price ({{currency_symbol()}})</label>
            <input value="{{get_register('pv_price')}}" type="number" name="pv_price" class="form-control">
        </div>
        <div class="col-md-2">
            <br>
            <button class="btn btn-primary btn-block">UPDATE</button>
        </div>
    </form>


    <br>
    <div class="row">
        <div class="col">
            <h6>All Packages </h6>
        </div>
        <div class="col text-end">
            <a style="margin-bottom:10px" data-bs-toggle='modal' data-bs-target='#create_package' href="#" class="badge bg-primary">Create Package</a>
        </div>
    </div>
    

    <div class="table-responsive table-nowrap">
        <table class="table table-hover table-bordered text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Cashback</th>
                    <th>Discount</th>
                    <th>Level</th>
                    <th>Services</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @php $no = tableNumber(10) @endphp 
                @foreach($packages as $package)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$package->name}}</td>
                        <td>{{format_with_cur($package->cost)}}</td>
                        <td>{{format_with_cur($package->cashback)}}</td>
                        <td>{{$package->discount}}%</td>
                        <td>{{$package->max_gen}}</td>
                        <td>
                            @if(!empty($package->services))
                                @foreach($all_services as $name => $key)
                                    @if(in_array($key, $package->services))
                                        <span class="badge bg-secondary">{{make_readable($name)}}</span>
                                    @endif 
                                @endforeach 
                            @else  
                                --
                            @endif 
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-target="#update_package{{$package->id}}" data-bs-toggle="modal">Edit</button>
                           
                            @include('app.admin.settings.package.update_package_modal')
                            @if($package->total_users <= 0)
                                <form action="{{route('admin.package.destroy', $package->id)}}" method="POST" style='display:inline'>
                                    @csrf 
                                    @method('delete')
                                    <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif 
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$packages->links()}}
</div>


<script>
window.onload = ()=>{
    $('.create_package_form').submit(function(e){
        e.preventDefault(); // Prevent the default form submission
        var form = $(e.currentTarget);
        var msg = form.find('.msg')
        var btn = form.find('button')
        var btn_content = btn.text(); 
        var url = btn.attr('src'); 
        msg.html(''); 
        loadButton(btn)
        $.ajax({
            type: 'POST',
            url: url, // Replace with your server endpoint
            data: form.serialize(),
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
@stop 