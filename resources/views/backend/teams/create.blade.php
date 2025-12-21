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
                    <li class="breadcrumb-item">
                        <a href="{{ route('teams') }}">{{ __('teams.breadcrumb_teams') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('teams.create_team.title') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('teams.create_team.title') }}</h2>
            <small>{{ __('teams.create_team.description') }}</small>
        </div>

        <a href="{{ route('teams') }}" class="btn btn-gray-800 animate-up-2">
            <i class="fas fa-arrow-left me-2"></i> {{ __('teams.create_team.back') }}
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('teams.store') }}" method="POST">
                @csrf

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label class="form-label">
                            {{ __('teams.create_team.team_name') }} <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" placeholder="{{ __('teams.create_team.team_name_placeholder') }}"
                            required>

                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('teams.create_team.status') }}</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="1" @selected(old('status', '1') == '1')>{{ __('teams.status_active') }}</option>
                            <option value="0" @selected(old('status') == '0')>{{ __('teams.status_deactive') }}</option>
                        </select>

                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">{{ __('teams.create_team.note') }}</label>
                        <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror"
                            placeholder="{{ __('teams.create_team.note_placeholder') }}">{{ old('note') }}</textarea>

                        @error('note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <button class="btn btn-primary mt-3 animate-up-2">
                    <i class="bi bi-save me-1"></i> {{ __('teams.create_team.save_team') }}
                </button>
            </form>

        </div>
    </div>
@endsection
