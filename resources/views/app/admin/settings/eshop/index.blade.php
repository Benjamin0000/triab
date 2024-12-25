@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="nk-block-head nk-block-head-sm">
        <div class="nk-block-between">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Eshop Settings</h4>
            </div>
        </div>
    </div>
    <br>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col">
                    <h6>Categories</h6> 
                </div>
                <div class="col text-end">
                    <button data-bs-target="#create_category" data-bs-toggle='modal' class="btn btn-sm btn-primary">Create</button>
                </div>
            </div>
            <br>
           
            <div class="table-responsive table-nowrap">
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Logo</th>
                            <th>Total Shops</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                            <tr>
                                <td>{{$category->name}}</td>
                                <td><img src='{{Storage::url($category->icon)}}' width="50" /></td>
                                <td>
                                    {{$category->total_shops()}}
                                </td>
                                <td>
                                    <button data-bs-toggle="modal" data-bs-target="#update_category{{$category->id}}" class="btn btn-info btn-sm">Edit</button>
                                    <form action="{{route('admin.delete_eshop_category', $category->id)}}" method="POST" style="display: inline">
                                        @csrf 
                                        @method('delete')
                                        <button onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                    @include('app.admin.settings.eshop.update_category_modal')
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-md-6">

        </div>
    </div>



@include('app.admin.settings.eshop.create_category_modal')
</div>
@stop 