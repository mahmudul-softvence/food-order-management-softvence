@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">User List</li>
                </ol>
            </nav>

            <h2 class="h4">User Management</h2>
            <p class="mb-0 text-muted">View system users with roles & access type.</p>
        </div>
        @can('user.create')
            <div>
                <a href="{{ route('users.create') }}" class="btn btn-primary animate-up-2">
                    <i class="bi bi-plus-circle me-1"></i> Add User
                </a>
            </div>
        @endcan
    </div>

    <form method="GET" action="{{ route('users') }}">
        <div class="table-settings mb-4">
            <div class="row g-4">

                <div class="col-6 col-md-4 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search by name or number">
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-users"></i></span>
                        <select name="role" class="form-select">
                            <option value="all">All Users</option>
                            <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="vendor" {{ request('role') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xxl-2 d-flex justify-content-end justify-content-md-start gap-3">
                    <button type="submit" class="btn btn-primary order-2 order-md-0">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    <a href="{{ route('users') }}" class="btn btn-secondary">Clear</a>
                </div>

            </div>
        </div>
    </form>


    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Employee Id</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($users as $index => $user)
                    <tr>
                        <td><span class="fw-bold">{{ $index + 1 }}</span></td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>
                            @if ($user->employee_number != null)
                                {{ $user->employee_number }}
                            @else
                                <span class="badge bg-warning">Vendor</span>
                            @endif
                        </td>
                        @php
                            $role = $user->getRoleNames()->first();
                        @endphp

                        <td>
                            <span
                                class="badge
                                    @if ($role == 'admin') bg-danger
                                    @elseif($role == 'employee') bg-info
                                    @elseif($role == 'vendor') bg-warning
                                    @else bg-tertiary @endif
                                ">
                                {{ ucfirst($role) }}
                            </span>
                        </td>

                        <td>
                            @if (auth()->user()->hasRole('admin'))
                                <div class="form-check form-switch">
                                    <input class="form-check-input status-toggle" type="checkbox"
                                        id="switch-{{ $user->id }}" data-id="{{ $user->id }}"
                                        {{ $user->status == 1 ? 'checked' : '' }}>
                                    <label
                                        class="form-check-label {{ $user->status == 1 ? 'text-primary' : 'text-danger' }}"
                                        for="switch-{{ $user->id }}">
                                        {{ $user->status == 1 ? 'Active' : 'Deactive' }}
                                    </label>
                                </div>
                            @else
                                <span class="badge {{ $user->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user->status ? 'Active' : 'Deactive' }}
                                </span>
                            @endif
                        </td>



                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm rounded-circle btn-outline-gray-700 dropdown-toggle"
                                    type="button" id="actionDropdown{{ $user->id }}" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $user->id }}">
                                    <li>
                                        <a class="dropdown-item viewUser" href="javascript:void(0)"
                                            data-id="{{ $user->id }}">
                                            <i class="bi bi-eye me-2"></i> View
                                        </a>
                                    </li>

                                    @can('user.edit')
                                        <li>
                                            <a class="dropdown-item" href="{{ route('users.edit', $user->id) }}">
                                                <i class="bi bi-pencil me-2"></i> Edit
                                            </a>
                                        </li>
                                    @endcan

                                    @can('user.delete')
                                        @if ($user->id != auth()->id())
                                            <li>
                                                <a class="dropdown-item text-danger" href="#"
                                                    onclick="deleteUser({{ $user->id }})">
                                                    <i class="bi bi-trash me-2"></i> Delete
                                                </a>
                                            </li>
                                        @endif
                                    @endcan

                                    @if ($user->id != auth()->id())
                                        <li>
                                            <a class="dropdown-item text-warning"
                                                href="{{ route('users.loginAs', $user->id) }}">
                                                <i class="bi bi-mask me-2"></i> Login as User
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>

                            <form id="delete-form-{{ $user->id }}" action="{{ route('users.delete', $user->id) }}"
                                method="POST" class="d-none">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>


                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>


    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="u_name">-</span></p>
                    <p><strong>Phone:</strong> <span id="u_phone">-</span></p>
                    <p><strong>Role:</strong> <span id="u_role">-</span></p>
                    <p><strong>Team:</strong> <span id="u_team">-</span></p>
                    <p><strong>Employee No:</strong> <span id="u_employee">-</span></p>
                    <p><strong>Floor:</strong> <span id="u_floor">-</span></p>
                    <p><strong>Row:</strong> <span id="u_row">-</span></p>
                    <p><strong>Seat:</strong> <span id="u_seat">-</span></p>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).on('change', '.status-toggle', function() {
            let id = $(this).data('id');
            let isChecked = $(this).prop('checked');
            let label = $(this).next('label');

            $.ajax({
                url: "/users/update/status/" + id,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(res) {
                    if (isChecked) {
                        label.text('Active').removeClass('text-danger').addClass('text-primary');
                    } else {
                        label.text('Deactive').removeClass('text-success').addClass('text-danger');
                    }
                    toastr.success('Status updated');
                },
                error: function() {
                    toastr.error('Update failed');
                    $("#switch-" + id).prop('checked', !isChecked);
                }
            });
        });
    </script>

    <script>
        $(document).on('click', '.viewUser', function() {
            var id = $(this).data('id');

            $('#u_name,#u_phone,#u_role,#u_team,#u_employee,#u_floor,#u_row,#u_seat').text('-');

            $.ajax({
                url: "/users/show/" + id,
                type: 'GET',
                dataType: 'json',
                success: function(res) {

                    $('#u_name').text(res.name ?? 'N/A');
                    $('#u_phone').text(res.phone ?? 'N/A');
                    $('#u_role').text(res.role ?? 'N/A');

                    if (res.role == "vendor") {
                        $('#u_team').parent().hide();
                        $('#u_employee').parent().hide();
                        $('#u_floor').parent().hide();
                        $('#u_row').parent().hide();
                        $('#u_seat').parent().hide();
                    } else {
                        $('#u_team').parent().show().find('#u_team').text(res.team ?? 'N/A');
                        $('#u_employee').parent().show().find('#u_employee').text(res.employee_number ??
                            'N/A');
                        $('#u_floor').parent().show().find('#u_floor').text(res.floor ?? 'N/A');
                        $('#u_row').parent().show().find('#u_row').text(res.row ?? 'N/A');
                        $('#u_seat').parent().show().find('#u_seat').text(res.seat_number ?? 'N/A');
                    }

                    new bootstrap.Modal(document.getElementById('userModal')).show();
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert("Failed to load user details.");
                }
            });
        });
    </script>


    <script>
        function deleteUser(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Deleting this user cannot be undone.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
