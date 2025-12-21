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
                    <li class="breadcrumb-item active">{{ __('teams.breadcrumb_teams') }}</li>
                </ol>
            </nav>

            <h2 class="h4">{{ __('teams.title') }}</h2>
            <small class="mb-0">{{ __('teams.description') }}</small>
        </div>

        <a href="{{ route('teams.create') }}" class="btn btn-primary mt-3 mt-md-0 animate-up-2">
            <i class="bi bi-plus-circle me-2"></i> {{ __('teams.add_team') }}
        </a>
    </div>

    <div class="table-settings mb-4">
        <form action="{{ route('teams') }}" method="GET">
            <div class="row g-3">
                <div class="col-12 col-md-3 col-xxl-3">
                    <div class="input-group me-2 me-lg-3">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="{{ __('teams.search_placeholder') }}">
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xxl-2 d-flex justify-content-end justify-content-md-start gap-3">
                    <button type="submit" class="btn btn-primary order-2 order-md-0">
                        <i class="bi bi-funnel me-1"></i> {{ __('teams.filter') }}
                    </button>
                    <a href="{{ route('teams') }}" class="btn btn-secondary">{{ __('teams.clear') }}</a>
                </div>
            </div>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="card-title">{{ __('teams.table.title') ?? __('teams.title') }}</h5>
                </div>

                <div class="card-body table-wrapper table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('teams.table.id') }}</th>
                                <th>{{ __('teams.table.name') }}</th>
                                <th>{{ __('teams.table.note') }}</th>
                                <th>{{ __('teams.table.total_users') }}</th>
                                <th>{{ __('teams.table.status') }}</th>
                                <th>{{ __('teams.table.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($teams as $index => $team)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ Str::limit($team->note, 50, '...') }}</td>
                                    <td>
                                        <span class="text-info">{{ $team->users->count() }}</span>
                                        {{ __('teams.table.users') ?? 'Users' }}
                                    </td>
                                    <td>
                                        @if ($team->status == 1)
                                            <span class="badge bg-success">{{ __('teams.status_active') }}</span>
                                        @else
                                            <span class="badge bg-danger">{{ __('teams.status_deactive') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teams.edit', $team->id) }}"
                                                class="btn btn-sm btn-secondary text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-danger"
                                                onclick="deleteTeam({{ $team->id }})">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </div>

                                        <form id="delete-form-{{ $team->id }}"
                                            action="{{ route('teams.delete', $team->id) }}" method="POST" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">
                                        {{ __('teams.empty') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $teams->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function deleteTeam(id) {
            Swal.fire({
                title: "{{ __('teams.delete_confirm_title') }}",
                text: "{{ __('teams.delete_confirm_text') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "{{ __('teams.delete_confirm_yes') }}",
                cancelButtonText: "{{ __('teams.delete_confirm_cancel') }}"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
@endsection
