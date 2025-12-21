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
                    <li class="breadcrumb-item"><a href="{{ route('teams') }}">{{ __('teams.breadcrumb_teams') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('teams.edit_team.breadcrumb_edit') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('teams.edit_team.title') }}</h2>
            <small>{{ __('teams.edit_team.description') }}</small>
        </div>

        <a href="{{ route('teams') }}" class="btn btn-gray-800 animate-up-2">
            <i class="fas fa-arrow-left me-2"></i> {{ __('teams.edit_team.back') }}
        </a>
    </div>

    <div class="card border-0 shadow mb-4">
        <div class="card-body">
            <form action="{{ route('teams.update', $team->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('teams.edit_team.team_name') }} <span
                                class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $team->name) }}"
                            placeholder="{{ __('teams.edit_team.team_name_placeholder') }}" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">{{ __('teams.edit_team.status') }}</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="1" {{ old('status', $team->status) == 1 ? 'selected' : '' }}>
                                {{ __('teams.status_active') }}</option>
                            <option value="0" {{ old('status', $team->status) == 0 ? 'selected' : '' }}>
                                {{ __('teams.status_deactive') }}</option>
                        </select>
                        @error('status')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">{{ __('teams.edit_team.note') }}</label>
                        <textarea name="note" rows="4" class="form-control @error('note') is-invalid @enderror"
                            placeholder="{{ __('teams.edit_team.note_placeholder') }}">{{ old('note', $team->note) }}</textarea>
                        @error('note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button class="btn btn-primary mt-3 animate-up-2">
                    <i class="bi bi-save me-1"></i> {{ __('teams.edit_team.update_team') }}
                </button>
            </form>
        </div>
    </div>
@endsection
