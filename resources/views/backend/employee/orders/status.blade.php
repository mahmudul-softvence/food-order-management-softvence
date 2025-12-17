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
                    <li class="breadcrumb-item active">Orders</li>
                </ol>
            </nav>
            <h2 class="h4">All Orders</h2>
            <small class="mb-0">Manage and track all customer orders here.</small>
        </div>
    </div>

    <form action="" method="GET">
        <div class="table-settings mb-4">
            <div class="row">

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Search order ID"
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>
                        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>
                        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        <select class="form-select" name="payment_status">
                            <option value="">All Payment</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                        </select>
                    </div>
                </div>


                <div class="col-12 col-md-3 col-xxl-2 d-flex gap-3">
                    <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>

                    <a href="{{ url()->current() }}" class="btn btn-secondary">Clear</a>
                </div>

            </div>
        </div>
    </form>


    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order ID</th>
                    <th>Vendor</th>
                    <th>Food Name</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($orders as $index => $order)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $order->order_no }}</td>
                        <td>{{ $order->vendor->name }}</td>
                        <td>{{ $order->food->name }}</td>
                        <td>{{ $order->total_price }}</td>
                        <td>
                            <span
                                class="badge @if ($order->payment_status == 'paid') bg-success
                            @else
                            bg-danger @endif">{{ ucfirst($order->payment_status) }}</span>
                        </td>
                        @php
                            $colors = [
                                'pending' => 'bg-warning',
                                'delivered' => 'bg-tertiary',
                                'received' => 'bg-success',
                                'cancelled' => 'bg-danger',
                            ];
                        @endphp

                        <td>
                            <span class="badge {{ $colors[$order_status] ?? 'bg-secondary' }}">
                                {{ ucfirst($order_status) }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            @can('employee.order.cancel')
                                @if ($order->status == 'pending')
                                    <button type="button" class="btn btn-sm btn-danger text-white cancelOrderBtn"
                                        data-id="{{ $order->id }}">
                                        <i class="bi bi-x-circle me-1"></i> Cancel
                                    </button>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).on('click', '.cancelOrderBtn', function() {

            let id = $(this).data('id');

            Swal.fire({
                title: "Are you sure?",
                text: "You want to cancel this order!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Cancel it",
                cancelButtonText: "No",
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: `/employee/order/cancel/${id}`,
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(res) {

                            if (res.status === 'success') {
                                Swal.fire("Cancelled!", res.message, "success");
                                setTimeout(() => location.reload(),
                                    1200);
                            } else {
                                Swal.fire("Error", res.message, "error");
                            }
                        }
                    });

                }
            });

        });
    </script>
@endsection
