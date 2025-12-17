<?php

namespace App\Http\Controllers;

use App\Models\FoodCategory;
use Illuminate\Http\Request;
use App\Helpers\UploadHelper;

class FoodCategoryController extends Controller
{
    public function index()
    {
        $categories = FoodCategory::get();
        return view('backend.food_categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.food_categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'status'      => 'required',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'end_time.after' => 'End time must be greater than start time',
        ]);

        $image = UploadHelper::handleUpload($request->file('image'), 'uploads/food_category/');

        FoodCategory::create([
            'name'        => $request->name,
            'description' => $request->description,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => $request->status,
            'image'       => $image,
        ]);

        return to_route('food_category')->with('success', 'Food Category Created Successfully');
    }

    public function edit($id)
    {
        $category = FoodCategory::findOrFail($id);
        return view('backend.food_categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $category = FoodCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'nullable',
            'start_time'  => 'required',
            'end_time'    => 'required|after:start_time',
            'status'      => 'required',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
        ], [
            'end_time.after' => 'End time must be greater than start time',
        ]);

        $image = UploadHelper::handleUpload($request->file('image'), 'uploads/food_category/', $category->image);

        $category->update([
            'name'        => $request->name,
            'description' => $request->description,
            'start_time'  => $request->start_time,
            'end_time'    => $request->end_time,
            'status'      => $request->status,
            'image'       => $image,
        ]);

        return to_route('food_category')->with('success', 'Category Updated Successfully');
    }

    public function destroy($id)
    {
        $category = FoodCategory::findOrFail($id);

        if ($category->image && file_exists(public_path($category->image))) {
            unlink(public_path($category->image));
        }

        $category->delete();

        return back()->with('success', 'Category deleted successfully.');
    }
}
