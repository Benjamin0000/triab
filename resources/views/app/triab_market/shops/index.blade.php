@extends('app.layout')
@section('content')

<div class="container-fluid py-4">
    <!-- Filter Form -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Filter {{$category->name}} by state and city</h5>
                    <form action="" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label for="state" class="form-label">State</label>
                            <select id="state" name="state" class="form-select">
                                <option value="">Select State</option>
                                <!-- Dynamically populate states -->
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="city" class="form-label">City</label>
                            <select id="city" name="city" class="form-select">
                                <option value="">Select City</option>
                                <!-- Dynamically populate cities based on state -->
                            </select>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Shops Listing -->
    <div class="row">
        @foreach ($shops as $shop)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <img src="{{ Storage::url($shop->logo) }}" class="card-img-top" alt="{{ $shop->name }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $shop->name }}</h5>
                    <p class="card-text">
                        <strong>Address:</strong> {{ $shop->address }}<br>
                        <strong>City:</strong> {{ $shop->city }}<br>
                        <strong>State:</strong> {{ $shop->state }}
                    </p>
                    <p class="card-text">
                        {{ Str::limit($shop->description, 200) }}
                    </p>
                </div>
                <div class="card-footer text-end">
                    <a href="" class="btn btn-sm btn-primary btn-block">Enter shop</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- using load more pagination --}}

    <div class="row">
        <div class="col-12">
            {{ $shops->links() }}
        </div>
    </div>
</div>

@stop
