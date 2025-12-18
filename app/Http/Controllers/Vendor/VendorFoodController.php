<?php

namespace App\Http\Controllers\Vendor;

use App\Helpers\UploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\TodayMeal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorFoodController extends Controller
{
    public function index(Request $request)
    {
        $query = Food::with('todayMeals')->where('user_id', Auth::id());

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $food_categories = FoodCategory::get();

        $foods = $query->latest()->paginate(10);

        return view('backend.vendor.foods.index', compact('foods', 'food_categories'));
    }



    public function create()
    {
        $categories = FoodCategory::get();
        return view('backend.vendor.foods.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'today_price' => 'required_if:is_today,on|nullable|numeric|min:1',
            'today_stock' => 'required_if:is_today,on|nullable|numeric|min:1',
        ], [
            'food_category_id.required_if' => 'Today Food Category is required when Today Meal is selected.',
            'today_price.required_if' => 'Today price is required when Today Meal is selected.',
            'today_stock.required_if' => 'Today stock is required when Today Meal is selected.',
        ]);

        $imagePath = UploadHelper::handleUpload($request->file('image'), 'uploads/food/');

        $food = Food::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
            'user_id' => Auth::id()
        ]);

        if ($request->is_today) {
            TodayMeal::create([
                'food_category_id' => $request->food_category_id,
                'food_id' => $food->id,
                'price' => $request->today_price,
                'stock' => $request->today_stock,
                'status' => 1,
            ]);
        }

        return redirect()->route('vendor.food')
            ->with('success', 'Food saved successfully!');
    }


    public function edit($id)
    {
        $food = Food::with('todayMeal')->findOrFail($id);
        $categories = FoodCategory::all();

        $anotherToday = TodayMeal::whereDate('created_at', today())
            ->whereHas('food', function ($q) use ($food) {
                $q->where('food_category_id', $food->food_category_id);
            })
            ->where('food_id', '!=', $food->id)
            ->where('status', 1)
            ->exists();

        return view('backend.vendor.foods.edit', compact('food', 'categories', 'anotherToday'));
    }





    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable|mimes:jpg,jpeg,png,webp|max:2048',
            'today_price' => 'required_if:is_today,on|nullable|numeric|min:1',
            'today_stock' => 'required_if:is_today,on|nullable|numeric|min:1',
        ], [
            'today_price.required_if' => 'Today price is required if Today Meal is selected.',
            'today_stock.required_if' => 'Today stock is required if Today Meal is selected.',
            'food_category_id.required_if' => 'Today Food Category is required if Today Meal is selected.',
        ]);

        $food = Food::findOrFail($id);

        $imagePath = UploadHelper::handleUpload(
            $request->file('image'),
            'uploads/food/',
            $food->image
        );

        $food->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $imagePath,
            'status' => $request->status,
        ]);

        if ($request->is_today) {

            TodayMeal::updateOrCreate(
                [
                    'food_id' => $food->id,
                    'created_at' => today()
                ],
                [
                    'food_category_id' => $request->food_category_id,
                    'price' => $request->today_price,
                    'stock' => $request->today_stock,
                    'status' => 1,
                ]
            );
        } else {
            TodayMeal::where('food_id', $food->id)
                ->whereDate('created_at', today())
                ->delete();
        }

        return redirect()->route('vendor.food')->with('success', 'Food updated successfully!');
    }


    public function destroy($id)
    {
        $food = Food::findOrFail($id);

        if ($food->image && file_exists(public_path($food->image))) {
            unlink(public_path($food->image));
        }

        $food->delete();

        return back()->with('success', 'Food deleted successfully.');
    }

    public function set_today_meal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'food_id'               => 'required|exists:food,id',
            'food_category_id'      => 'required|array|min:1',
            'food_category_id.*'    => 'exists:food_categories,id',
            'price'                 => 'required|numeric|min:0',
            'stock'                 => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        foreach ($data['food_category_id'] as $categoryId) {
            TodayMeal::create([
                'food_id'          => $data['food_id'],
                'food_category_id' => $categoryId,
                'price'            => $data['price'],
                'stock'            => $data['stock'],
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Today meal set successfully'
        ]);
    }


    public function restock_today_meal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'meal_id' => 'required|exists:today_meals,id',
            'stock'   => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $meal = TodayMeal::findOrFail($request->meal_id);

        if (!$meal->created_at->isToday()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'You can only restock today’s meals'
            ], 403);
        }

        $meal->increment('stock', $request->stock);

        return response()->json([
            'status'  => 'success',
            'message' => 'Stock updated successfully',
            'new_stock' => $meal->stock
        ]);
    }

    public function delete(Request $request)
    {
        $meal = TodayMeal::where('id', $request->id)
            ->where('vendor_id', Auth::id())
            ->first();

        if (!$meal) {
            return response()->json([
                'message' => 'Meal not found'
            ], 404);
        }

        if ($meal->order_count > 0) {
            return response()->json([
                'message' => 'This meal already has orders and cannot be deleted'
            ], 403);
        }

        $meal->delete();

        return response()->json([
            'message' => 'Meal deleted successfully'
        ]);
    }
}
