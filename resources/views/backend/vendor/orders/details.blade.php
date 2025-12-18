<div class="p-3">

    <h5 class="mb-3">Order #{{ $order->id }}</h5>

    <table class="table table-bordered mb-4">
        <tbody>
            <tr>
                <th style="width: 40%">Food</th>
                <td>{{ $order->food->name }}</td>
            </tr>

            <tr>
                <th>Customer</th>
                <td>{{ $order->user->name }}</td>
            </tr>

            <tr>
                <th>Quantity</th>
                <td>{{ $order->quantity }}</td>
            </tr>

            <tr>
                <th>Total Price</th>
                <td>{{ number_format($order->total_price, 2) }}</td>
            </tr>

            <tr>
                <th>Status</th>
                <td>{{ ucfirst($order->status) }}</td>
            </tr>

            <tr>
                <th>Payment Status</th>
                <td>
                    @php
                        $payBadge = $order->payment_status === 'paid' ? 'bg-success' : 'bg-danger';
                    @endphp
                    <span class="badge {{ $payBadge }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mb-3">
        <label class="fw-bold">Filter Payment Status:</label>
        <select id="paymentFilter" class="form-select w-25">
            <option value="">All</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
        </select>
    </div>

    <h5 class="mb-3">Orders of {{ $order->user->name }}</h5>

    @if ($latestOrders->count())
        <table class="table table-bordered" id="latestOrdersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Order No</th>
                    <th>Food</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($latestOrders as $item)
                    <tr data-payment="{{ $item->payment_status }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->order_no }}</td>
                        <td>{{ $item->food->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>${{ number_format($item->total_price, 2) }}</td>

                        <td>
                            @php
                                $badgeClass =
                                    [
                                        'pending' => 'bg-warning',
                                        'processing' => 'bg-info',
                                        'delivered' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                    ][$item->status] ?? 'bg-secondary';
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>

                        <td>
                            @php
                                $payClass = $item->payment_status == 'paid' ? 'bg-success' : 'bg-danger';
                            @endphp
                            <span class="badge {{ $payClass }}">
                                {{ ucfirst($item->payment_status) }}
                            </span>
                        </td>

                        <td>{{ $item->created_at->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p class="text-muted">No previous orders found.</p>
    @endif

</div>



<script>
    $("#paymentFilter").on("change", function() {
        let value = $(this).val();

        $("#latestOrdersTable tbody tr").each(function() {

            let rowStatus = $(this).data("payment");

            if (value === "" || value === rowStatus) {
                $(this).show();
            } else {
                $(this).hide();
            }

        });
    });
</script>
