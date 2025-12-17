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
                    <li class="breadcrumb-item active">Teams</li>
                </ol>
            </nav>

            <h2 class="h4">User Teams</h2>
            <small class="mb-0">Manage all user teams & group members.</small>
        </div>

        <a href="{{ route('teams.create') }}" class="btn btn-primary mt-3 mt-md-0 animate-up-1">
            <i class="bi bi-plus-circle me-2"></i>Add Team
        </a>
    </div>

    <div class="table-settings mb-4">
        <form action="{{ route('teams') }}" method="GET">
            <div class="row g-3">

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group me-2 me-lg-3">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search team name">
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xxl-2 d-flex justify-content-end justify-content-md-start gap-3">
                    <button class="btn btn-primary order-2 order-md-0"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ route('teams') }}" class="btn btn-secondary">Clear</a>
                </div>

            </div>
        </form>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow">
                <div class="card-header">
                    <h5 class="card-title">Teams</h5>
                </div>

                <div class="card-body table-wrapper table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Note</th>
                                <th>Total Users</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($teams as $index => $team)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $team->name }}</td>
                                    <td>{{ Str::limit($team->note, 50, '...') }}</td>
                                    <td><span class="text-info">{{ $team->users->count() }}</span> Users
                                    </td>
                                    <td>
                                        @if ($team->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Deactive</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('teams.edit', $team->id) }}"
                                                class="btn btn-warning text-white">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            <a href="#" class="btn btn-danger"
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
                                        No teams found
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
                title: "Are you sure?",
                text: "This action cannot be undone!",
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
