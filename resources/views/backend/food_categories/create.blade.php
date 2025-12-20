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
                    <li class="breadcrumb-item active">{{ __('foods.create_category') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('foods.create_category') }}</h2>
            <small class="mb-0">{{ __('foods.add_category_description') }}</small>
        </div>

        <a href="{{ route('food_category') }}" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> {{ __('messages.back') }}
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('food_category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('foods.category_name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                            placeholder="{{ __('foods.enter_category_name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('foods.status') }} <span class="text-danger">*</span>
                        </label>
                        <select name="status" class="form-select">
                            <option value="1" selected>{{ __('foods.active') }}</option>
                            <option value="0">{{ __('foods.inactive') }}</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">{{ __('foods.description') }}</label>
                        <textarea name="description" rows="4" class="form-control" placeholder="{{ __('foods.short_description') }}">{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('foods.start_time') }} <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input type="text" name="start_time" value="{{ old('start_time') }}"
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
                            <input type="text" name="end_time" value="{{ old('end_time') }}"
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
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fas fa-save me-2"></i> {{ __('foods.save_category') }}
                </button>


            </form>
        </div>
    </div>
@endsection
