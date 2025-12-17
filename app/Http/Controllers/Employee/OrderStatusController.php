<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderStatusController extends Controller
{
    public function index(Request $request, $order_status)
    {
        $query = Order::with('user')
            ->where('user_id', Auth::id())
            ->where('status', $order_status);

        if ($request->search) {
            $query->where('id', 'LIKE', "%$request->search%");
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date && $request->end_date) {

            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        $orders = $query->paginate(10)->appends($request->all());

        return view('backend.employee.orders.status', compact('orders', 'order_status'));
    }


    public function cancel($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status !== 'pending') {
            return response()->json(['status' => 'error', 'message' => 'Only pending orders can be cancelled']);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['status' => 'success', 'message' => 'Order cancelled successfully']);
    }
}
