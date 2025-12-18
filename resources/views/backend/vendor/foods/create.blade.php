@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block mb-2">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('vendor.food') }}">Foods</a></li>
                    <li class="breadcrumb-item active">Create Food</li>
                </ol>
            </nav>

            <h2 class="h4">Create a New Food</h2>
            <small>Add food details and publish it to your menu.</small>
        </div>

        <a href="{{ route('vendor.food') }}" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <form action="{{ route('vendor.food.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Food Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="Enter food name">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-4 mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        @error('image')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>


                    <div class="col-12 mb-5">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="Write short description...">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_today" id="todayToggle">
                            <label class="form-check-label" for="todayToggle">Make it Today Meal</label>
                        </div>
                    </div>

                    <div id="todayFields" style="display:none;">
                        <div class="row">

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category</label>
                                <select name="food_category_id" class="form-select">
                                    <option selected disabled>Select Category</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                                @error('food_category_id')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Today Price</label>
                                <input type="number" name="today_price" class="form-control"
                                    placeholder="Enter today's price" value="{{ old('today_price') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Today Stock</label>
                                <input type="number" name="today_stock" class="form-control"
                                    placeholder="Enter today's stock" value="{{ old('today_stock') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mt-3"><i class="fas fa-save me-2"></i> Save Food</button>
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
