@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('settings.settings') }}</li>
                </ol>
            </nav>
            <h2 class="h4">{{ __('settings.site_settings') }}</h2>
            <small class="mb-0">{{ __('settings.manage_settings') }}</small>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="mb-3">{{ __('settings.menu') }}</h5>
                    <ul class="nav nav-pills square nav-fill flex-column vertical-tab">
                        <li class="nav-item">
                            <a class="btn btn-primary w-100 mb-3" href="{{ route('settings') }}">
                                <i class="bi bi-sliders me-2"></i> {{ __('settings.general') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary w-100 mb-3" href="{{ route('settings.logo') }}">
                                <i class="bi bi-image me-2"></i> {{ __('settings.logo_favicon') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-outline-primary w-100 mb-3" href="{{ route('settings.contact') }}">
                                <i class="bi bi-telephone me-2"></i> {{ __('settings.contact_info') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{ __('settings.general') }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">{{ __('settings.site_name') }}</label>
                            <input type="text" name="site_name" class="form-control"
                                value="{{ $setting->site_name ?? '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('settings.site_description') }}</label>
                            <textarea name="site_description" class="form-control" rows="3">{{ $setting->site_description ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('settings.footer_text') }}</label>
                            <input type="text" name="footer_text" class="form-control"
                                value="{{ $setting->footer_text ?? '' }}">
                        </div>

                        <button class="btn btn-primary mt-2" type="submit">
                            <i class="bi bi-save me-1"></i> {{ __('settings.save_changes') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
