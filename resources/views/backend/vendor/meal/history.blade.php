@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block mb-2">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a></li>
                    <li class="breadcrumb-item active">Meal History</li>
                </ol>
            </nav>

            <h2 class="h4">Today's Meal History</h2>
            <p>Track meal price, stock & availability.</p>
        </div>
    </div>

    <div class="table-settings mb-4">
        <form method="GET">
            <div class="row align-items-center">

                <div class="col-md-4 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ $search }}" class="form-control"
                            placeholder="Search by Food name...">
                    </div>
                </div>

                <div class="col-md-4 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" name="date" class="form-control">
                    </div>
                </div>

                <div class="col-md-4 col-xxl-3">
                    <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>
                    <a href="{{ route('vendor.meal.history') }}" class="btn btn-secondary ms-2">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card card-body shadow table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Food Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Total Order</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($meals as $key => $meal)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $meal->food->name }}</td>
                        <td>${{ $meal->price }}</td>
                        <td>
                            {{ $meal->stock }}

                            @if ($meal->created_at->isToday())
                                <button class="btn btn-xs btn-info ms-1 rounded-1" data-bs-toggle="modal"
                                    data-bs-target="#restockModal" data-meal-id="{{ $meal->id }}"
                                    data-stock="{{ $meal->stock }}">
                                    Re-Stock
                                </button>
                            @endif
                        </td>

                        <td>
                            {{ $meal->order_count }}
                        </td>

                        <td>
                            @if ($meal->created_at->isToday())
                                <span class="badge {{ $meal->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $meal->status ? 'Available' : 'Unavailable' }}
                                </span>
                            @elseif ($meal->created_at->isFuture())
                                <span class="badge bg-warning text-dark">
                                    Not for Today
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    End
                                </span>
                            @endif
                        </td>

                        <td>{{ $meal->created_at->format('Y-m-d') }}</td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="#" class="btn btn-primary"><i class="fas fa-eye"></i></a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No meal history found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-3">
            {{ $meals->links() }}
        </div>


        <div class="modal fade" id="restockModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-body">
                        <form id="restockForm" action="{{ route('vendor.food.stock.update') }}" method="POST">
                            @csrf

                            <input type="hidden" name="meal_id" id="restockMealId">

                            <div class="mb-2">
                                <label class="form-label small">Current Stock</label>
                                <input type="text" class="form-control form-control-sm" id="currentStock" readonly>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small">Add Stock</label>
                                <input type="number" class="form-control form-control-sm" name="stock" min="1"
                                    required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                Update Stock
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            $('#restockModal').on('show.bs.modal', function(event) {
                let button = $(event.relatedTarget);

                $('#restockMealId').val(button.data('meal-id'));
                $('#currentStock').val(button.data('stock'));

                $('#restockForm input[name="stock"]').val('');
            });

            $('#restockForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(data) {
                        if (data.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Updated!',
                                text: data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Error', data.message ?? 'Something went wrong', 'error');
                        }
                    },
                    error: function(xhr) {

                        let message = 'Validation failed';

                        if (xhr.responseJSON) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire('Error', message, 'error');
                    }
                });
            });

        });
    </script>
@endsection
