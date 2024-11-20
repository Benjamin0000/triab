@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h3 class="nk-block-title page-title">Packages</h3>
            </div>
        </div>
    </div>

    <form class="row">
        <div class="col-md-3">
            <label for="">Cashback (%)</label>
            <input type="text" name="cashback" class="form-control" placeholder="eg 1,2,4,5,5">
        </div>
        <div class="col-md-3">
            <label for="">PV</label>
            <input type="text" name="cashback" class="form-control" placeholder="eg 1,2,4,5,5">
        </div>
        <div class="col-md-3">

        </div>
        <div class="col-md-3">

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
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Cost</th>
                    <th>Discount</th>
                    <th>Level</th>
                </tr>
            </thead>
            <tbody>
                @php $no = tableNumber(10) @endphp 
                @foreach($packages as $package)
                    <tr>
                        <td>{{$no++}}</td>
                        <td>{{$package->name}}</td>
                        <td>{{format_with_cur($package->cost)}}</td>
                        <td>{{$package->discount}}</td>
                        <td>{{$package->max_gen}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@include('app.admin.settings.create_package_modal')
@stop 