<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use App\Models\Order;
use App\Models\TodayMeal;
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
}
