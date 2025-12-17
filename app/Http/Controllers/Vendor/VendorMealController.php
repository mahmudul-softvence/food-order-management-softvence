<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TodayMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class VendorMealController extends Controller
{
    public function history(Request $request)
    {
        $search = $request->search;
        $date = $request->date;

        $meals = TodayMeal::with('food')
            ->when($search, function ($q) use ($search) {
                $q->whereHas('food', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            })
            ->when($date, function ($q) use ($date) {
                $q->whereDate('created_at', $date);
            })
            ->whereHas('food', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->paginate(10)
            ->appends(request()->query());

        return view('backend.vendor.meal.history', compact('meals', 'search', 'date'));
    }



    public function payment_history(Request $request)
    {
        $base = Order::with('food')
            ->where('vendor_id', Auth::id());

        if ($request->payment_status) {
            $base->where('payment_status', $request->payment_status);
        }

        if ($request->status) {
            $base->where('status', $request->status);
        }

        if ($request->search) {
            $search = $request->search;

            $base->where(function ($q) use ($search) {
                $q->where('total_price', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('phone', 'LIKE', "%{$search}%");
                    })
                    ->orWhereHas('food', function ($f) use ($search) {
                        $f->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->start_date && $request->end_date) {
            $base->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay()
            ]);
        }

        $total_payment = (clone $base)->where('payment_status', 'paid')->sum('total_price');
        $total_due     = (clone $base)->where('payment_status', 'unpaid')->sum('total_price');
        $total_deliverd = (clone $base)->where('status', 'delivered')->count();

        $orders = $base->latest()->paginate(10)->withQueryString();

        return view('backend.vendor.payment_history.index', compact(
            'total_payment',
            'total_due',
            'total_deliverd',
            'orders'
        ));
    }
}
