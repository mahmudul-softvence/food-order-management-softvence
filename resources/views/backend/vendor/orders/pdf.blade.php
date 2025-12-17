<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: DejaVu Sans;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        p {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <h3>Orders ({{ ucfirst($order_status) }})</h3>
    <p><strong>Date:</strong> {{ $today }}</p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Order No</th>
                <th>Customer</th>
                <th>Employee ID</th>
                <th>Quantity</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $order->order_no }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->user->employee_number }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ ucfirst($order->payment_status) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
