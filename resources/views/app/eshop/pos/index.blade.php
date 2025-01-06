@extends('app.layout')
@section('content')
<div class="container-fluid">
    <div class="header-section">
        <h4 class="page-title">{{$shop->name}}</h4>
        <div>{{$shop->storeID}}</div>
    </div>
    <br>
    <div>
        <button class="btn btn-primary btn-sm" data-bs-toggle='modal' data-bs-target='#create_admin'>Create staff</button>
        <a href="{{route('pos.index', $shop->id)}}" target="_blank" class="btn btn-primary btn-sm">Sales point</a>
    </div>
    <br>
    @include('app.eshop.pos.create_admin')
    <div class="table-responsive">
        <table class="table table-nowrap">
            <thead>
                <tr class="text-center">
                    <th>No</th>
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
                            <div>
                                @if($staff->status)
                                    <span class="bg-success badge">Active</span>
                                @else  
                                    <span class="bg-danger badge">Suspended</span>
                                @endif 
                            </div>
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
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#update_admin{{$staff->id}}">Edit</button>

                            <form style="display: inline" action="{{route('eshop.delete_staff', $staff->id)}}" method="POST">
                                @csrf 
                                @method('delete')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure about this?')">Delete</button>
                            </form>
                        </td>
                        <td>
                            {{$staff->created_at->isoFormat('lll')}}
                            @include('app.eshop.pos.update_admin')
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{$staffs->links()}}
</div>
@stop