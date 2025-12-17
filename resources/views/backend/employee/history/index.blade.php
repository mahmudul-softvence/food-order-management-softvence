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

            <h2 class="h4">Today's Payment History</h2>
            <p class="mb-0">Check customer payments, methods & status.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('employee.payment_history') }}">
        <div class="table-settings mb-4">
            <div class="row align-items-center">

                <div class="col-6 col-md-3 col-xxl-2">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-cash-coin"></i>
                        </span>

                        <select name="payment_status" class="form-select">
                            <option value="">All Payments</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                Paid
                            </option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                Unpaid
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-4 col-xxl-2">
                    <div class="input-group fmxw-400">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input name="start_date" type="date" class="form-control" value="{{ request('start_date') }}">
                    </div>
                </div>

                <div class="col-md-4 col-xxl-2">
                    <div class="input-group fmxw-400">
                        <span class="input-group-text">
                            <i class="fas fa-calendar"></i>
                        </span>
                        <input name="end_date" type="date" class="form-control" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-md-4 col-xxl-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>

                    <a href="{{ route('employee.payment_history') }}" class="btn btn-secondary ms-2">
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
                    <th>Category</th>
                    <th>Vendor Name</th>
                    <th>Food Name</th>
                    <th>Amount</th>
                    <th>Payment Status</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($payments as $payment)
                    <tr>
                        <td><span class="fw-bold">{{ $loop->iteration }}</span></td>

                        <td> {{ $payment->todayMeal->category->name ?? '-' }}</td>

                        <td> {{ $payment->vendor->name }}</td>

                        <td>{{ $payment->food->name }}</td>

                        <td>$ {{ $payment->total_price }}</td>

                        <td>
                            <span class="badge {{ $payment->payment_status == 'paid' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($payment->payment_status) }}
                            </span>
                        </td>

                        @php
                            $statusColors = [
                                'pending' => 'bg-warning',
                                'delivered' => 'bg-tertiary',
                                'received' => 'bg-success',
                                'cancelled' => 'bg-danger',
                            ];
                        @endphp

                        <td>
                            <span class="badge {{ $statusColors[$payment->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>

                        <td>{{ $payment->created_at->format('d-m-Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">
                            No payment history found for today
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

        <div class="mt-4">
            {{ $payments->links() }}
        </div>
    </div>
@endsection
