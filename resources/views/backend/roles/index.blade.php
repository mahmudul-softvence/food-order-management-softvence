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
                    <li class="breadcrumb-item active">{{ __('roles.breadcrumb_roles') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('roles.title') }}</h2>
            <small class="mb-0">{{ __('roles.description') }}</small>
        </div>

        <a href="{{ route('roles.create') }}" class="btn btn-primary mt-3 mt-md-0 animate-up-2">
            <i class="bi bi-plus-circle me-2"></i>{{ __('roles.add_role') }}
        </a>
    </div>

    <div class="table-settings mb-4">
        <form action="{{ route('roles') }}" method="GET">
            <div class="row g-4">
                <div class="col-12 col-md-4 col-xxl-3">
                    <div class="input-group me-2 me-lg-3">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control"
                            placeholder="{{ __('roles.search_placeholder') }}" value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xxl-2 d-flex justify-content-end justify-content-md-start gap-3">
                    <button class="btn btn-primary order-2 order-md-0"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ route('roles') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="card-title">{{ __('roles.title') }}</h5>
                </div>

                <div class="card-body table-wrapper table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('roles.table.id') }}</th>
                                <th>{{ __('roles.table.name') }}</th>
                                <th>{{ __('roles.table.total_users') }}</th>
                                <th>{{ __('roles.table.total_active') }}</th>
                                <th>{{ __('roles.table.total_deactive') }}</th>
                                <th>{{ __('roles.table.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::ucfirst($role->name) }}</td>
                                    <td><span class="text-primary">{{ $role->users->count() }}</span></td>
                                    <td><span class="text-success">{{ $role->users->where('status', 1)->count() }}</span>
                                    </td>
                                    <td><span class="text-danger">{{ $role->users->where('status', 0)->count() }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            @can('role.edit')
                                                <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-tertiary">
                                                    <i class="bi bi-pencil me-1"></i> {{ __('roles.table.manage') }}
                                                </a>
                                            @endcan

                                            @can('role.delete')
                                                <a class="btn btn-danger" onclick="confirmDelete({{ $role->id }})">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            @endcan
                                        </div>

                                        <form id="delete-form-{{ $role->id }}"
                                            action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
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
                title: "{{ __('roles.delete_confirm_title') }}",
                text: "{{ __('roles.delete_confirm_text') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __('roles.delete_confirm_yes') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
