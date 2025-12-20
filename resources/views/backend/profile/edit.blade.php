@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="#"><i class="bi bi-house-door fs-6"></i></a></li>
                    <li class="breadcrumb-item active">{{ __('profile.edit_profile') }}</li>
                </ol>
            </nav>
            <h2 class="h4">{{ __('profile.edit_profile') }}</h2>
            <small class="text-muted">{{ __('profile.update_details') }}</small>
        </div>
    </div>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-7">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">{{ __('profile.general_information') }}</h2>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="name">{{ __('profile.name') }}</label>
                            <input class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                type="text" placeholder="{{ __('profile.name') }}" value="{{ old('name', $user->name) }}"
                                required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone">{{ __('profile.phone') }}</label>
                            <input class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                type="text" placeholder="{{ __('profile.phone') }}"
                                value="{{ old('phone', $user->phone) }}" required>
                            @error('phone')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        @if ($user->hasRole(['employee', 'admin', 'office_staff']))
                            <div class="col-md-6 mb-3">
                                <label for="employee_number">{{ __('profile.employee_number') }}</label>
                                <input class="form-control @error('employee_number') is-invalid @enderror"
                                    id="employee_number" name="employee_number" type="text"
                                    value="{{ old('employee_number', $user->employee_number) }}">
                                @error('employee_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="team_id">{{ __('profile.team') }}</label>
                                <select name="team_id" id="team_id" class="form-select">
                                    <option value="">{{ __('profile.select_one') }}</option>
                                    @foreach ($teams as $team)
                                        <option @if ($team->id == $user->team_id) selected @endif
                                            value="{{ $team->id }}">{{ $team->name }}</option>
                                    @endforeach
                                </select>
                                @error('team_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="floor">{{ __('profile.floor') }}</label>
                                <input class="form-control @error('floor') is-invalid @enderror" id="floor"
                                    name="floor" type="text" value="{{ old('floor', $user->floor) }}">
                                @error('floor')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="row">{{ __('profile.row') }}</label>
                                <input class="form-control @error('row') is-invalid @enderror" id="row" name="row"
                                    type="text" value="{{ old('row', $user->row) }}">
                                @error('row')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="seat_number">{{ __('profile.seat_number') }}</label>
                                <input class="form-control @error('seat_number') is-invalid @enderror" id="seat_number"
                                    name="seat_number" type="text" value="{{ old('seat_number', $user->seat_number) }}">
                                @error('seat_number')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @elseif ($user->hasRole(['vendor']))
                            <div class="col-md-6 mb-3">
                                <label for="nid">{{ __('profile.nid_number') }}</label>
                                <input class="form-control @error('nid') is-invalid @enderror" id="nid" name="nid"
                                    type="text" value="{{ old('nid', $user->nid) }}">
                                @error('nid')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        @endif
                    </div>

                    <h2 class="h5 my-4">{{ __('profile.password') }}</h2>
                    <div class="row">
                        <div class="col-sm-6 mb-3">
                            <label for="password">{{ __('profile.password') }}</label>
                            <input class="form-control @error('password') is-invalid @enderror" id="password"
                                name="password" type="password" placeholder="{{ __('profile.password') }}">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-sm-6 mb-3">
                            <label for="password_confirmation">{{ __('profile.confirm_password') }}</label>
                            <input class="form-control" id="password_confirmation" name="password_confirmation"
                                type="password" placeholder="{{ __('profile.confirm_password') }}">
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-gray-800 mt-2 animate-up-2" type="submit">
                            <i class="bi bi-save me-1"></i> {{ __('profile.save_all') }}
                        </button>
                    </div>

                </div>
            </div>

            <div class="col-md-5">
                <div class="card card-body border-0 shadow mb-4">
                    <h2 class="h5 mb-4">{{ __('profile.profile_photo') }}</h2>
                    <input type="file" name="profile_image" class="dropify" data-default-file="{{ $user->avater }}"
                        data-allowed-file-extensions="jpg png jpeg webp" data-max-file-size="2M" />

                    @if ($user->hasRole('vendor'))
                        <h2 class="h5 my-4">{{ __('profile.documents') }}</h2>

                        <div class="mb-3">
                            <label for="nid_image">{{ __('profile.nid_image') }}</label>
                            <input type="file" name="nid_image" class="dropify"
                                data-default-file="{{ $user->nid_image }}">
                        </div>

                        <div class="mb-3">
                            <label for="trade_licence">{{ __('profile.trade_licence') }}</label>
                            <input type="file" name="trade_licence" class="dropify"
                                data-default-file="{{ $user->trade_licence }}">
                        </div>

                        <div class="mb-3">
                            <label for="visiting_card">{{ __('profile.visiting_card') }}</label>
                            <input type="file" name="visiting_card" class="dropify"
                                data-default-file="{{ $user->visiting_card }}">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
@endsection
