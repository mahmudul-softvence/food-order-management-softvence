@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('roles') }}">
                            <i class="bi bi-house-door fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="{{ route('roles') }}">Roles</a></li>
                    <li class="breadcrumb-item active">Edit Role</li>
                </ol>
            </nav>

            <h2 class="h4">Edit Role</h2>
            <small class="mb-0">Update role name or modify assigned permissions.</small>
        </div>

        <a href="{{ route('roles') }}" class="btn btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter role name"
                            value="{{ old('name', $role->name) }}" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="border rounded p-4 permission-container">

                            <div class="row g-4">
                                @php
                                    $groupedPermissions = $permissions->groupBy('group');
                                @endphp

                                @foreach ($groupedPermissions as $group => $perms)
                                    <div class="col-md-6 mb-3">
                                        <div class="border rounded p-4 h-100">
                                            <h5 class="fw-bold text-primary mb-3">
                                                <input type="checkbox" class="form-check-input select-group me-2"
                                                    id="group-{{ $group }}">
                                                <label class="form-check-label" for="group-{{ $group }}">
                                                    {{ ucfirst($group) }}
                                                </label>
                                            </h5>

                                            @foreach ($perms as $permission)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input child-perm group-{{ $group }}"
                                                        type="checkbox" name="permissions[]" id="perm{{ $permission->id }}"
                                                        value="{{ $permission->name }}"
                                                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="perm{{ $permission->id }}">
                                                        {{ $permission->name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>


                        </div>
                    </div>
                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fas fa-save me-2"></i> Update Role
                </button>

            </form>

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('.select-group').each(function() {
                const group = $(this).attr('id').replace('group-', '');
                const allChecked = $('.group-' + group).length === $('.group-' + group + ':checked').length;
                $(this).prop('checked', allChecked);
            });

            $('.select-group').on('change', function() {
                const group = $(this).attr('id').replace('group-', '');
                $('.group-' + group).prop('checked', $(this).prop('checked'));
            });

            $('.child-perm').on('change', function() {
                const classes = $(this).attr('class').split(/\s+/);
                classes.forEach(function(cls) {
                    if (cls.startsWith('group-')) {
                        const group = cls.replace('group-', '');
                        const allChecked = $('.group-' + group).length === $('.group-' + group +
                            ':checked').length;
                        $('#group-' + group).prop('checked', allChecked);
                    }
                });
            });

        });
    </script>
@endsection
