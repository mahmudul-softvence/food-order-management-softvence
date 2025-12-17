@extends('backend.master')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-4">
        <div>
            <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}">
                            <svg class="icon icon-xxs" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <small>Manage and track all orders here.</small>
        </div>
    </div>


    <form method="GET">
        <div class="table-settings mb-4">
            <div class="row g-4">

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Search Order ID / Number">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>
                        <input name="start_date" value="{{ request('start_date') }}" type="date" class="form-control"
                            placeholder="Start Date">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar-week"></i></span>
                        <input name="end_date" value="{{ request('end_date') }}" type="date" class="form-control"
                            placeholder="End Date">
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                        <select class="form-select" name="payment_status">
                            <option value="">All Payments</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>Unpaid
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-3 col-xxl-2 d-flex gap-2">
                    <button class="btn btn-primary"><i class="bi bi-funnel me-1"></i> Filter</button>

                    <a href="{{ request()->url() }}" class="btn btn-secondary">Clear</a>
                </div>

                <div class="col-md-2  text-end">
                    <a href="{{ route('vendor.orders.export.pdf', $order_status) }}?{{ request()->getQueryString() }}"
                        class="btn btn-danger">
                        <i class="bi bi-file-earmark-pdf"></i> Download PDF
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
                    <th>Order No</th>
                    <th>Customer</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($orders as $key => $order)
                    <tr>
                        <td>{{ $orders->firstItem() + $key }}</td>
                        <td>{{ $order->order_no }}</td>
                        <td>{{ $order->user->name ?? 'NA' }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ number_format($order->total_price, 2) }}</td>

                        <td>
                            <div class="d-flex align-items-center justify-content-start gap-3">
                                <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : 'danger' }}">
                                    {{ ucfirst($order->payment_status) }}
                                </span>

                                @if ($order->payment_status == 'unpaid' && $order->status != 'cancelled')
                                    <form action="{{ route('vendor.order.markPaid', $order->id) }}" method="POST"
                                        class="markPaidForm d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-xs btn-info rounded-1 markPaidBtn"
                                            data-bs-toggle="tooltip" data-bs-custom-class="small-tooltip"
                                            data-bs-placement="top" title="Mark as Paid">
                                            <i class="bi bi-check-circle me-1"></i>Mark Paid
                                        </button>
                                    </form>
                                @endif

                            </div>
                        </td>

                        <td>
                            @php
                                $badgeClass =
                                    [
                                        'pending' => 'bg-warning',
                                        'delivered' => 'bg-tertiary',
                                        'received' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ][$order->status] ?? 'bg-secondary';
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>

                        <td>{{ $order->created_at->format('Y-m-d') }}</td>

                        <td>
                            @if ($order->status == 'pending')
                                <button class="btn btn-danger text-white btn-sm me-2" data-bs-toggle="tooltip"
                                    data-bs-custom-class="small-tooltip" data-bs-placement="left" title="Cancel Order"
                                    onclick="submitStatusForm({{ $order->id }}, 'cancelled')">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            @endif

                            <button class="btn btn-gray-700 btn-sm me-2" data-bs-toggle="tooltip" data-bs-placement="top"
                                data-bs-custom-class="small-tooltip" title="View Order"
                                onclick="viewOrder({{ $order->id }})">
                                <i class="bi bi-eye"></i>
                            </button>

                            @if ($order->status == 'pending')
                                <button class="btn btn-success text-white btn-sm me-2" data-bs-toggle="tooltip"
                                    data-bs-custom-class="small-tooltip" data-bs-placement="top" title="Mark as Delivered"
                                    onclick="submitStatusForm({{ $order->id }}, 'delivered')">
                                    <i class="bi bi-check-circle me-2"></i>Delivered
                                </button>
                            @endif

                            @if ($order->status == 'delivered')
                                <button class="btn btn-success text-white btn-sm" data-bs-toggle="tooltip"
                                    data-bs-custom-class="small-tooltip" data-bs-placement="top" title="Mark as Received"
                                    onclick="submitStatusForm({{ $order->id }}, 'received')">
                                    <i class="bi bi-check-circle me-2"></i>Received
                                </button>
                            @endif

                            <form id="statusForm" action="{{ route('vendor.order.status.update', 'ID_HERE') }}"
                                method="POST" class="d-none">
                                @csrf
                                <input type="hidden" name="status" id="statusInput">
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No Orders Found</td>
                    </tr>
                @endforelse
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
        function submitStatusForm(orderId, status) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to change the order status.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('statusForm');

                    form.action = form.action.replace('ID_HERE', orderId);

                    document.getElementById('statusInput').value = status;

                    form.submit();
                }
            });
        }
    </script>



    <script>
        document.querySelectorAll('.markPaidBtn').forEach(button => {
            button.addEventListener('click', function(e) {
                let form = this.closest('form');

                Swal.fire({
                    title: "Are you sure?",
                    text: "This order will be marked as paid!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, mark it!",
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>

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
