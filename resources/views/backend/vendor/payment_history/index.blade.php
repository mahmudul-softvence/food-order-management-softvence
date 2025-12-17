@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div class="d-block mb-4 mb-md-0">
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">Payment History</li>
                </ol>
            </nav>

            <h2 class="h4">Payment History</h2>
            <p class="mb-0 text-muted">Check customer payments, methods & status.</p>
        </div>
    </div>

    @php
        $activePaid = request('payment_status') === 'paid';
        $activeDue = request('payment_status') === 'unpaid';
        $activeDelivered = request('status') === 'delivered';
    @endphp

    <div class="row mb-5 g-4">

        <div class="col-sm-2 col-md-3 col-lg-2">
            <a href="{{ route('vendor.meal.payment_history', ['payment_status' => 'paid']) }}">
                <div class="card card-body border-0 shadow bg-primary text-white">
                    <div class="d-flex align-items-center gap-4">
                        <div class="fs-3">
                            <i class="bi bi-coin"></i>
                        </div>
                        <div>
                            <h6 class="fw-normal ">Total Paid</h6>
                            <h4 class="m-0 mt-2 fw-bolder">
                                {{ $total_payment }}
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-2 col-md-3 col-lg-2">
            <a href="{{ route('vendor.meal.payment_history', ['payment_status' => 'unpaid']) }}">
                <div class="card card-body border-0 shadow bg-danger text-white">
                    <div class="d-flex align-items-center gap-4">
                        <div class="fs-3 ">
                            <i class="bi bi-hourglass"></i>
                        </div>
                        <div>
                            <h6 class="fw-normal">Total Due</h6>
                            <h4 class="m-0 mt-2 fw-bolder">
                                {{ $total_due }}
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-2 col-md-3 col-lg-2">
            <a href="{{ route('vendor.meal.payment_history', ['status' => 'delivered']) }}">
                <div class="card card-body border-0 shadow bg-tertiary text-white">
                    <div class="d-flex align-items-center gap-4">
                        <div class="fs-3">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div>
                            <h6 class="fw-normal">
                                Total Delivered
                            </h6>
                            <h4 class="m-0 mt-2 fw-bolder">
                                {{ $total_deliverd }}
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="col-sm-2 col-md-3 col-lg-2">
            <a href="{{ route('vendor.meal.payment_history', ['status' => 'received']) }}">
                <div class="card card-body border-0 shadow bg-success text-white">
                    <div class="d-flex align-items-center gap-4">
                        <div class="fs-3">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-normal">
                                Total Received
                            </h6>
                            <h4 class="m-0 mt-2 fw-bolder">
                                {{ $total_deliverd }}
                            </h4>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>




    <form method="GET" action="{{ route('vendor.meal.payment_history') }}">
        <div class="table-settings mb-4">
            <div class="row align-items-center g-4">

                <div class="col-6 col-md-4 col-xxl-2">
                    <div class="input-group fmxw-400">
                        <span class="input-group-text">
                            <i class="fas fa-search"></i>
                        </span>
                        <input name="search" class="form-control" placeholder="Search By Phone / Food"
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xxl-2">
                    <div class="input-group fmxw-400">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input name="start_date" class="form-control" type="date" value="{{ request('start_date') }}">
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xxl-2">
                    <div class="input-group fmxw-400">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input name="end_date" class="form-control" type="date" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xxl-2 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    <a href="{{ route('vendor.meal.payment_history') }}" class="btn btn-secondary">
                        Clear
                    </a>
                </div>

            </div>
        </div>
    </form>


    <div class="card card-body border-0 shadow table-wrapper table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer Name</th>
                    <th>Category</th>
                    <th>Food Name</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @php $i = ($orders->currentPage() - 1) * $orders->perPage() + 1; @endphp

                @foreach ($orders as $order)
                    <tr>
                        <td><span class="fw-bold">{{ $i++ }}</span></td>

                        <td>{{ $order->user->name ?? 'N/A' }}</td>

                        <td>{{ $order->food->category->name ?? 'N/A' }}</td>

                        <td>{{ $order->food->name ?? 'N/A' }}</td>

                        <td>{{ $order->total_price }} Tk</td>

                        <td>
                            @if ($order->payment_status == 'paid')
                                <span class="badge bg-success">Paid</span>
                            @else
                                <span class="badge bg-danger">Unpaid</span>
                            @endif
                        </td>

                        @php
                            $statusMap = [
                                'pending' => 'bg-warning',
                                'delivered' => 'bg-tertiary',
                                'received' => 'bg-success',
                                'cancelled' => 'bg-danger',
                            ];
                        @endphp

                        <td>
                            <span class="badge {{ $statusMap[$order->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>{{ $order->created_at->format('Y-m-d') }}</td>

                        <td>
                            <div class="btn-group btn-group-sm">
                                <button class="btn btn-gray-700 btn-sm me-2" data-bs-toggle="tooltip"
                                    data-bs-placement="left" data-bs-custom-class="small-tooltip" title="View Details"
                                    onclick="viewOrder({{ $order->id }})">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>

    <div class="modal fade" id="orderDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="orderDetailsBody">
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        function viewOrder(id) {
            $("#orderDetailsModal").modal("show");

            $("#orderDetailsBody").html(`
            <div class="text-center py-5">
                <div class="spinner-border"></div>
                <p class="mt-2">Loading...</p>
            </div>
        `);

            $.ajax({
                url: "{{ route('vendor.order.details') }}",
                type: "GET",
                data: {
                    id: id
                },
                success: function(response) {
                    $("#orderDetailsBody").html(response);
                },
                error: function() {
                    $("#orderDetailsBody").html(`
                    <div class="alert alert-danger">
                        Failed to load order details.
                    </div>
                `);
                }
            });
        }
    </script>
@endsection
