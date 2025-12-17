<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    public function index(Request $request, $order_status)
    {
        $query = Order::with('user')
            ->where('vendor_id', Auth::id())
            ->where('status', $order_status);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_no', 'LIKE', "%{$request->search}%")
                    ->orWhere('id', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('created_at', [$start, $end]);
        }

        $orders = $query->latest()->paginate(10)->appends($request->all());

        return view('backend.vendor.orders.index', compact('orders', 'order_status'));
    }


    public function exportMealPdf(Request $request, $order_status)
    {
        $query = Order::with('user')
            ->where('vendor_id', Auth::id())
            ->where('status', $order_status);

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('order_no', 'LIKE', "%{$request->search}%")
                    ->orWhere('id', 'LIKE', "%{$request->search}%");
            });
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end   = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $orders = $query->latest()->get();

        $today = Carbon::today()->format('d-m-Y');

        $pdf = Pdf::loadView(
            'backend.vendor.orders.pdf',
            compact('orders', 'order_status', 'today')
        )->setPaper('a4', 'portrait');

        return $pdf->download('orders-' . $order_status . '.pdf');
    }



    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('vendor_id', Auth::id())->findOrFail($id);

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'Order status updated successfully.');
    }

    public function markPaid($id)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = 'paid';
        $order->save();

        return back()->with('success', 'Order marked as paid.');
    }

    public function orderDetails(Request $request)
    {
        $order = Order::with('food', 'user')->findOrFail($request->id);

        $latestOrders = Order::where('user_id', $order->user_id)
            ->where('vendor_id', Auth::id())
            ->latest()
            ->get();


        return view('backend.vendor.orders.details', compact('order', 'latestOrders'));
    }
}
