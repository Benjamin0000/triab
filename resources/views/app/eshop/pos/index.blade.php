@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="header-section">
        <h4 class="page-title">{{$shop->name}}</h4>
        <div>{{$shop->storeID}}</div>
    </div>
    <br>
    <div>
        <button class="btn btn-primary" data-bs-toggle='modal' data-bs-target='#create_admin'>Create staff</button>
    </div>
    @include('app.eshop.pos.create_admin')


    <div class="table-responsive">
        <table class="table table-nowrap">
            <thead>
                <tr class="text-center">
                    <th></th>
                    <th>Name</th>
                    <th>Role</th>
                    <th>Pass code</th>
                    <th>Actions</th>
                    <th>Created at</th>
                </tr>
            </thead>
            <tbody>
                @php $no = tableNumber(20) @endphp 
                @foreach($staffs as $staff)
                    <tr class="text-center">
                        <td>
                            {{$no++}}
                        </td>
                        <td>
                            {{$staff->name}}
                        </td>
                        <td>
                            @if($staff->admin)
                                Admin
                            @else 
                                Sales Rep.
                            @endif 
                        </td>
                        <td>
                            {{$staff->pass_code}}
                        </td>
                        <td>
                          
                        </td>
                        <td>
                            <button class="btn btn-sm btn-primary">Edit</button>
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$staffs->links()}}
</div>
@stop