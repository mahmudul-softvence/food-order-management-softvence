@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block mb-2">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.food') }}">Foods</a></li>
                    <li class="breadcrumb-item active">Edit Food</li>
                </ol>
            </nav>

            <h2 class="h4">Edit Food</h2>
            <small>Update food details and Today Meal status.</small>
        </div>

        <a href="{{ route('vendor.food') }}" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <form action="{{ route('vendor.food.update', $food->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Food Name</label>
                        <input type="text" name="name" value="{{ $food->name }}" class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ $food->status == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ $food->status == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control">{{ $food->description }}</textarea>
                    </div>

                    <div class="col-md-6 mb-5">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control">
                        <div class="mt-2">
                            @if ($food->image)
                                <img src="{{ asset($food->image) }}" width="120" class="mt-2 rounded">
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_today" id="todayToggle"
                                {{ $food->todayMeal && $food->todayMeal->created_at->isToday() ? 'checked' : '' }}>
                            <label class="form-check-label" for="todayToggle">Today Meal</label>
                        </div>
                    </div>

                    <div id="todayFields"
                        style="{{ $food->todayMeal && $food->todayMeal->created_at->isToday() ? '' : 'display:none' }}">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <select name="food_category_id" class="form-select">
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == $food->food_category_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Today Price</label>
                                <input type="number" name="today_price"
                                    value="{{ old('today_price', $food->todayMeal && $food->todayMeal->created_at->isToday() ? $food->todayMeal->price : '') }}"
                                    class="form-control @error('today_price') is-invalid @enderror"
                                    placeholder="Enter today's price">

                                @error('today_price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Today Stock</label>
                                <input type="number" name="today_stock"
                                    value="{{ old('today_stock', $food->todayMeal && $food->todayMeal->created_at->isToday() ? $food->todayMeal->stock : '') }}"
                                    class="form-control @error('today_stock') is-invalid @enderror"
                                    placeholder="Enter today's stock">

                                @error('today_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mt-3"><i class="fas fa-sync me-2"></i> Update Food</button>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(function() {
            $('#todayToggle').on('change', function() {
                $('#todayFields').slideToggle(this.checked);
            });
        });
    </script>
@endsection
