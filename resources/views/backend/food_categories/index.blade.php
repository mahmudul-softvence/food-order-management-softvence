@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('foods.food_categories') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('foods.food_categories') }}</h2>
            <small class="mb-0">{{ __('foods.manage_food_categories') }}</small>
        </div>

        @can('food_category.create')
            <a href="{{ route('food_category.create') }}" class="btn btn-primary mt-3 mt-md-0">
                <i class="bi bi-plus-circle me-2"></i>{{ __('foods.add_category') }}
            </a>
        @endcan
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="card-title">{{ __('foods.food_categories') }}</h5>
                </div>

                <div class="card-body table-wrapper table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>{{ __('foods.image') }}</th>
                                <th>{{ __('foods.name') }}</th>
                                <th>{{ __('foods.description') }}</th>
                                <th>{{ __('foods.start_time') }}</th>
                                <th>{{ __('foods.end_time') }}</th>
                                <th>{{ __('foods.status') }}</th>
                                <th>{{ __('foods.action') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($categories as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><img src="{{ asset($item->image) }}" width="50" height="50" class="rounded">
                                    </td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ Str::limit($item->description, 40) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->start_time)->format('h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->end_time)->format('h:i A') }}</td>

                                    <td>
                                        <span class="badge {{ $item->status ? 'bg-success' : 'bg-danger' }}">
                                            {{ $item->status ? __('foods.active') : __('foods.inactive') }}
                                        </span>
                                    </td>
                                    <td>
                                        @can('food_category.edit')
                                            <a href="{{ route('food_category.edit', $item->id) }}"
                                                class="btn btn-sm me-2 btn-secondary text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        @endcan

                                        @can('food_category.delete')
                                            <a class="btn btn-danger btn-sm" onclick="confirmDelete({{ $item->id }})">
                                                <i class="bi bi-trash"></i>
                                            </a>


                                            <form id="delete-form-{{ $item->id }}"
                                                action="{{ route('food_category.delete', $item->id) }}" method="POST"
                                                style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        @endcan
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">{{ __('foods.no_category_found') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
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
                confirmButtonText: "Yes, delete it"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
