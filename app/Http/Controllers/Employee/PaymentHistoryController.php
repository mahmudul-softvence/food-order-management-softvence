<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class PaymentHistoryController extends Controller
{

    public function payment_history(Request $request)
    {
        $query = Order::with(['food', 'todayMeal.category', 'user'])
            ->where('user_id', Auth::id());

        if ($request->search) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('total_price', 'LIKE', "%{$search}%")
                    ->orWhereHas('food', function ($f) use ($search) {
                        $f->where('name', 'LIKE', "%{$search}%");
                    });
            });
        }

        if ($request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        } else {
            $query->whereDate('created_at', Carbon::today());
        }

        $payments = $query->latest()->paginate(10)->withQueryString();

        return view('backend.employee.history.index', compact('payments'));
    }
}
