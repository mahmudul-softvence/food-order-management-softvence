@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}"><i class="bi bi-house-door fs-6"></i></a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('food_category') }}">{{ __('foods.food_categories') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('foods.edit_food_category') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('foods.edit_food_category') }}</h2>
            <small class="mb-0">{{ __('foods.update_category_information') }}</small>
        </div>

        <a href="{{ route('food_category') }}" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <form action="{{ route('food_category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('foods.category_name') }}</label>
                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                            class="form-control">
                        @error('name')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('foods.status') }}</label>
                        <select name="status" class="form-select">
                            <option value="1" {{ old('status', $category->status) == 1 ? 'selected' : '' }}>
                                {{ __('foods.active') }}
                            </option>
                            <option value="0" {{ old('status', $category->status) == 0 ? 'selected' : '' }}>
                                {{ __('foods.inactive') }}
                            </option>
                        </select>
                        @error('status')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">{{ __('foods.description') }}</label>
                        <textarea name="description" rows="4" class="form-control">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('foods.start_time') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="text" name="start_time"
                                value="{{ old('start_time', isset($category) ? \Carbon\Carbon::parse($category->start_time)->format('h:i A') : '') }}"
                                class="form-control timepicker" placeholder="Select start time">
                        </div>
                        @error('start_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('foods.end_time') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="text" name="end_time"
                                value="{{ old('end_time', isset($category) ? \Carbon\Carbon::parse($category->end_time)->format('h:i A') : '') }}"
                                class="form-control timepicker" placeholder="Select end time">
                        </div>
                        @error('end_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>



                    <div class="col-md-12 mb-3">
                        <label class="form-label">{{ __('foods.category_image') }}</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror

                        <div class="mt-3">
                            <img src="{{ asset($category->image) }}" width="100" height="100" class="border rounded">
                        </div>
                    </div>

                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fas fa-edit me-2"></i> {{ __('foods.update_category') }}
                </button>
            </form>


        </div>
    </div>
@endsection
