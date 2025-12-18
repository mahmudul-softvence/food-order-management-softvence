<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\Order;
use App\Models\TodayMeal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{

    public function index()
    {
        $categories = FoodCategory::get();

        $pending_orders = Order::with('user')
            ->where('vendor_id', Auth::id())
            ->where('status', 'pending')->paginate(6);

        $todaysMeals = TodayMeal::with('food')
            ->where('vendor_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->get()
            ->groupBy('food_category_id');


        return view('backend.dashboard.index', compact('categories', 'pending_orders', 'todaysMeals'));
    }



    public function bulkStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:cancelled,delivered',
            'order_ids' => 'required'
        ]);

        $ids = explode(',', $request->order_ids);

        Order::whereIn('id', $ids)
            ->where('vendor_id', auth()->id())
            ->update([
                'status' => $request->status
            ]);

        return back()->with('success', 'Orders updated successfully');
    }
}
