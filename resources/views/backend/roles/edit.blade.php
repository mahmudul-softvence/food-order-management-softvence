@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="#">
                            <i class="bi bi-house-door fs-6"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item"><a href="#">Roles</a></li>
                    <li class="breadcrumb-item active">Edit Role</li>
                </ol>
            </nav>

            <h2 class="h4">Edit Role</h2>
            <small class="mb-0">Update role name or modify assigned permissions.</small>
        </div>

        <a href="#" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">

            <form action="" method="POST">
                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter role name"
                            value="Admin" required>
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">Permissions</label>
                        <div class="border rounded p-3" style="max-height:260px; overflow-y:auto;">

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="p1" checked>
                                <label class="form-check-label" for="p1">User Create</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="p2" checked>
                                <label class="form-check-label" for="p2">User Edit</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="p3">
                                <label class="form-check-label" for="p3">User Delete</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="p4" checked>
                                <label class="form-check-label" for="p4">Food Manage</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="permissions[]" id="p5">
                                <label class="form-check-label" for="p5">Orders View</label>
                            </div>

                            @for ($i = 6; $i <= 15; $i++)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="permissions[]"
                                        id="p{{ $i }}">
                                    <label class="form-check-label" for="p{{ $i }}">Permission
                                        {{ $i }}</label>
                                </div>
                            @endfor

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
