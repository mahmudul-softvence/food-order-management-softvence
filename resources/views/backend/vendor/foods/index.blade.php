@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('foods.foods') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('foods.all_foods') }}</h2>
            <small class="mb-0 text-muted">{{ __('foods.manage_all_foods') }}</small>
        </div>

        @can('vendor.food.create')
            <a href="{{ route('vendor.food.create') }}" class="btn btn-gray-800">
                <i class="bi bi-plus-circle me-1"></i> {{ __('foods.new_food') }}
            </a>
        @endcan
    </div>

    <form method="GET">
        <div class="table-settings mb-4">
            <div class="row align-items-center">
                <div class="col-md-4 col-xxl-2">
                    <div class="input-group me-2 me-lg-3 fmxw-400">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="{{ __('foods.search_food') }}">
                    </div>
                </div>

                <div class="col-md-4 col-xxl-2">
                    <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> {{ __('messages.filter') }}</button>
                    <a href="{{ route('vendor.food') }}" class="btn btn-secondary ms-2"> {{ __('messages.clear') }}</a>
                </div>

            </div>
        </div>
    </form>



    <div class="card card-body shadow border-0 table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('foods.image') }}</th>
                    <th>{{ __('foods.name') }}</th>
                    <th>{{ __('foods.description') }}</th>
                    <th>{{ __('foods.status') }}</th>
                    <th>{{ __('foods.today_meal') }}</th>
                    <th>{{ __('foods.action') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($foods as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>

                        <td>
                            <img src="{{ asset($item->image) }}" width="45" class="rounded">
                        </td>

                        <td>{{ $item->name }}</td>

                        <td>{{ Str::limit($item->description, 40) }}</td>

                        <td>
                            @if ($item->status == 1)
                                <span class="badge bg-success">{{ __('foods.active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('foods.inactive') }}</span>
                            @endif
                        </td>

                        <td>
                            @php
                                $todayMeals = $item->todayMeals->filter(function ($meal) {
                                    return $meal->created_at->isToday();
                                });
                            @endphp

                            @if ($todayMeals->isNotEmpty())
                                @foreach ($todayMeals as $todayMeal)
                                    <div class="mb-1">
                                        <span class="badge bg-primary me-1">
                                            {{ __('foods.today') }} • ${{ $todayMeal->price }}
                                        </span>
                                        <span class="badge bg-secondary">
                                            {{ $todayMeal->category->name }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <button class="btn btn-xs rounded-1 btn-info" data-bs-toggle="modal"
                                    data-bs-target="#todayMealModal" data-food-id="{{ $item->id }}">
                                    <i class="bi bi-check-circle me-1"></i>
                                    {{ __('foods.set_today_meal') }}
                                </button>
                            @endif
                        </td>



                        <td>
                            @can('vendor.food.edit')
                                <a href="{{ route('vendor.food.edit', $item->id) }}"
                                    class="btn btn-sm me-2 btn-secondary text-white">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endcan

                            @can('vendor.food.delete')
                                <a class="btn btn-sm btn-danger" onclick="confirmDelete({{ $item->id }})">
                                    <i class="bi bi-trash"></i>
                                </a>

                                <form id="delete-form-{{ $item->id }}"
                                    action="{{ route('vendor.food.delete', $item->id) }}" method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endcan
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-3">
            {{ $foods->links() }}
        </div>

        <div class="modal fade" id="todayMealModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">

                <div class="modal-content">
                    <form id="todayMealForm">
                        @csrf

                        <input type="hidden" name="food_id" id="todayFoodId">

                        <div class="modal-header">
                            <h6 class="modal-title">{{ __('foods.confirm_today_meal') }}</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body border-0">
                            <div class="mb-3">
                                <label class="form-label">{{ __('foods.food_category') }}</label>

                                <select class="form-select select2" name="food_category_id[]" multiple
                                    data-placeholder="{{ __('foods.select_one') }}">
                                    @foreach ($food_categories as $food_category)
                                        <option value="{{ $food_category->id }}">
                                            {{ $food_category->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <small class="text-danger error-food_category_id"></small>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">{{ __('foods.price') }}</label>
                                <input type="number" class="form-control" name="price"
                                    placeholder="{{ __('foods.enter_price') }}">
                                <small class="text-danger error-price"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{{ __('foods.stock') }}</label>
                                <input type="number" class="form-control" name="stock"
                                    placeholder="{{ __('foods.enter_stock') }}">
                                <small class="text-danger error-stock"></small>
                            </div>
                        </div>

                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">
                                {{ __('foods.cancel') }}
                            </button>
                            <button type="submit" class="btn btn-sm btn-primary">
                                {{ __('foods.yes_set_meal') }}
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>


    </div>
@endsection


@section('script')
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this action!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>


    <script>
        $(document).ready(function() {

            $('#todayMealModal').on('shown.bs.modal', function() {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: "{{ __('foods.select_one') }}",
                    dropdownParent: $('#todayMealModal')
                });
            });

            $('#todayMealModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);
                let foodId = button.data('food-id');

                $('#todayFoodId').val(foodId);

                $('.text-danger').text('');
                $('#todayMealForm')[0].reset();

                $('.select2').val(null).trigger('change');
            });

            $('#todayMealForm').on('submit', function(e) {
                e.preventDefault();

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('vendor.food.set_today_meal') }}",
                    type: "POST",
                    data: formData,

                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                            $('#todayMealModal').modal('hide');
                            location.reload();
                        }
                    },

                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $('.text-danger').text('');

                            $.each(errors, function(key, value) {
                                $('.error-' + key).text(value[0]);
                            });
                        }
                    }
                });
            });

        });
    </script>
@endsection
