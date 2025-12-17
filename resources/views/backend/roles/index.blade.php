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
                    <li class="breadcrumb-item active">Roles</li>
                </ol>
            </nav>

            <h2 class="h4">User Roles</h2>
            <small class="mb-0">Manage all role of an user.</small>
        </div>

        <a href="{{ route('roles.create') }}" class="btn btn-primary mt-3 mt-md-0">
            <i class="bi bi-plus-circle me-2"></i>Add Role
        </a>
    </div>

    <div class="table-settings mb-4">
        <div class="row g-4">

            <div class="col-6 col-md-4 col-xxl-2">
                <div class="input-group me-2 me-lg-3 fmxw-400">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Search by role name">
                </div>
            </div>

            <div class="col-md-4 col-xxl-2 d-flex justify-content-end justify-content-md-start gap-2">
                <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                <button class="btn btn-secondary ms-2">Clear</button>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="card-title">Roles</h5>
                </div>

                <div class="card-body table-wrapper table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Total users</th>
                                <th>Total Active</th>
                                <th>Total Deactive</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($roles as $index => $role)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ Str::ucfirst($role->name) }}</td>
                                    <th><span class="text-primary">{{ $role->users->count() }}</span></th>
                                    <th><span class="text-success">{{ $role->users->where('status', 1)->count() }}</span>
                                    <th><span class="text-danger">{{ $role->users->where('status', 0)->count() }}</span>
                                    </th>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('roles.edit') }}" class="btn btn-tertiary">
                                                <i class="bi bi-pencil me-1"></i> Manage
                                            </a>

                                            <a href="#" class="btn btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>
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
