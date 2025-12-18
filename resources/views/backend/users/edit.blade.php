@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
                    <li class="breadcrumb-item active">Edit User</li>
                </ol>
            </nav>
            <h2 class="h4">Edit User</h2>
            <small>Update user information.</small>
        </div>

        <a href="{{ route('users') }}" class="btn btn-sm btn-gray-800">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="number" name="phone" value="{{ old('phone', $user->phone) }}"
                                    class="form-control @error('phone') is-invalid @enderror">
                                @error('phone')
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" id="role"
                                    class="form-select @error('role') is-invalid @enderror">
                                    <option value="employee"
                                        {{ old('role', $user->getRoleNames()->first()) == 'employee' ? 'selected' : '' }}>
                                        Employee
                                    </option>
                                    <option value="admin"
                                        {{ old('role', $user->getRoleNames()->first()) == 'admin' ? 'selected' : '' }}>Admin
                                    </option>
                                    <option value="vendor"
                                        {{ old('role', $user->getRoleNames()->first()) == 'vendor' ? 'selected' : '' }}>
                                        Vendor
                                    </option>
                                </select>
                                @error('role')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">New Password (optional)</label>
                                <input type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Leave empty to keep old password">
                                @error('password')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Re-enter password">
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6" id="vendorSection">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Employee Number</label>
                                <input type="text" name="employee_number"
                                    value="{{ old('employee_number', $user->employee_number) }}"
                                    class="form-control @error('employee_number') is-invalid @enderror">
                                @error('employee_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Team</label>
                                <select name="team_id" class="form-select @error('team_id') is-invalid @enderror">
                                    <option value="">Select one</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            {{ old('team_id', $user->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Floor</label>
                                <input type="text" name="floor" value="{{ old('floor', $user->floor) }}"
                                    class="form-control @error('floor') is-invalid @enderror">
                                @error('floor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Row</label>
                                <input type="text" name="row" value="{{ old('row', $user->row) }}"
                                    class="form-control @error('row') is-invalid @enderror">
                                @error('row')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Seat Number</label>
                                <input type="text" name="seat_number"
                                    value="{{ old('seat_number', $user->seat_number) }}"
                                    class="form-control @error('seat_number') is-invalid @enderror">
                                @error('seat_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>

                </div>

                <button class="btn btn-primary mt-3">
                    <i class="fas fa-save me-2"></i> Update User
                </button>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function toggleVendor() {
            $('#vendorSection').toggle($('#role').val() !== 'vendor');
        }
        $('#role').on('change', toggleVendor);
        toggleVendor();
    </script>
@endsection
