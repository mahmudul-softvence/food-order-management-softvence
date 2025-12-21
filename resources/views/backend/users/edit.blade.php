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

        <a href="{{ route('users') }}" class="btn btn-gray-800 animate-up-2">
            <i class="fas fa-arrow-left me-2"></i> Back
        </a>
    </div>

    <div class="card shadow border-0 mb-4">
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
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
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" id="role"
                                    class="form-select @error('role') is-invalid @enderror">
                                    <option value="">Select Role</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->name }}"
                                            {{ old('role', isset($user) ? $user->getRoleNames()->first() : '') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
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
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6" id="nonVendorSection">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Employee Number</label>
                                <input type="text" name="employee_number"
                                    value="{{ old('employee_number', $user->employee_number) }}" class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Team</label>
                                <select name="team_id" class="form-select">
                                    <option value="">Select one</option>
                                    @foreach ($teams as $team)
                                        <option value="{{ $team->id }}"
                                            {{ old('team_id', $user->team_id) == $team->id ? 'selected' : '' }}>
                                            {{ $team->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Floor</label>
                                <input type="text" name="floor" value="{{ old('floor', $user->floor) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Row</label>
                                <input type="text" name="row" value="{{ old('row', $user->row) }}"
                                    class="form-control">
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Seat Number</label>
                                <input type="text" name="seat_number"
                                    value="{{ old('seat_number', $user->seat_number) }}" class="form-control">
                            </div>

                        </div>
                    </div>

                    <div class="col-md-6" id="vendorSection" style="display:none;">
                        <div class="row">

                            <div class="col-md-12 mb-3">
                                <label class="form-label">NID Number</label>
                                <input type="text" name="nid" value="{{ old('nid', $user->nid ?? '') }}"
                                    class="form-control @error('nid') is-invalid @enderror" placeholder="Vendor NID Number">
                                @error('nid')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">NID Image</label>
                                <input type="file" name="nid_image" class="dropify"
                                    data-allowed-file-extensions="jpg jpeg png pdf" data-max-file-size="10M"
                                    @if (isset($user->nid_image)) data-default-file="{{ asset($user->nid_image) }}" @endif>
                                @error('nid_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trade Licence</label>
                                <input type="file" name="trade_licence" class="dropify"
                                    data-allowed-file-extensions="jpg jpeg png pdf" data-max-file-size="10M"
                                    @if (isset($user->trade_licence)) data-default-file="{{ asset($user->trade_licence) }}" @endif>
                                @error('trade_licence')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Visiting Card</label>
                                <input type="file" name="visiting_card" class="dropify"
                                    data-allowed-file-extensions="jpg jpeg png pdf" data-max-file-size="10M"
                                    @if (isset($user->visiting_card)) data-default-file="{{ asset($user->visiting_card) }}" @endif>
                                @error('visiting_card')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>


                </div>

                <button class="btn btn-primary mt-3 animate-up-2">
                    <i class="bi bi-save me-1"></i> Update User
                </button>

            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function toggleVendor() {
            let role = $('#role').val();

            if (role === 'vendor') {
                $('#vendorSection').show();
                $('#nonVendorSection').hide();
            } else {
                $('#vendorSection').hide();
                $('#nonVendorSection').show();
            }
        }

        $('#role').on('change', toggleVendor);
        toggleVendor();
    </script>
@endsection
