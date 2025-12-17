<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Order;
use App\Models\TodayMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function make_order()
    {
        $categories = FoodCategory::get();
        return view('backend.employee.orders.make_order', compact('categories'));
    }


    public function vendor_list($id)
    {
        $foods = TodayMeal::with('food')
            ->where('food_category_id', $id)
            ->whereDate('created_at', Carbon::today())
            ->get();

        $orders = Order::where('user_id', Auth::id())->get();

        return view('backend.employee.orders.vendor_list', compact('foods', 'orders'));
    }


    public function order(Request $request)
    {

        $request->validate([
            'quantity' => 'required|integer|min:1',
            'food_id' => 'required',
            'vendor_id' => 'required'
        ]);

        $food_id = $request->food_id;

        $meal = TodayMeal::where('food_id', $food_id)
            ->whereDate('created_at', Carbon::today())
            ->first();

        if (!$meal) {
            return back()->with('error', 'Meal not found for today.');
        }

        $availableStock = $meal->stock - $meal->order_count;

        if ($availableStock < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $user_id = Auth::id();
        $unit_price = (int)$meal->price;
        $total_price = $unit_price * (int)$request->quantity;

        $order = Order::create([
            'user_id' => $user_id,
            'vendor_id' => $request->vendor_id,
            'food_id' => $food_id,
            'quantity' => $request->quantity,
            'unit_price' => $unit_price,
            'total_price' => $total_price,
            'note' => $request->note,
            'today_meal_id' => $request->meal_id
        ]);

        $meal->increment('order_count', $request->quantity);

        return back()->with('success', 'Order placed successfully!');
    }
}
