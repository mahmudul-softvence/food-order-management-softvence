<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show profile edit page
     */
    public function index()
    {
        $user = Auth::user();
        $teams = Team::get();

        return view('backend.profile.edit', compact('user', 'teams'));
    }

    /**
     * Update profile info + password + avatar
     */

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'required|string|max:20',
            'password' => 'nullable|min:6|confirmed',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];


        if ($user->hasRole(['employee', 'admin', 'office_staff'])) {
            $rules = array_merge($rules, [
                'employee_number' => 'nullable|string|max:50',
                'team_id' => 'nullable|integer|exists:teams,id',
                'floor' => 'nullable|string|max:50',
                'row' => 'nullable|string|max:50',
                'seat_number' => 'nullable|string|max:50',
            ]);
        } elseif ($user->hasRole('vendor')) {
            $rules = array_merge($rules, [
                'nid' => 'nullable|string|max:50',
                'nid_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'trade_licence' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
                'visiting_card' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            ]);
        }

        $data  = $request->validate($rules);


        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($user->hasRole(['employee', 'admin', 'office_staff'])) {
            $user->employee_number = $request->employee_number;
            $user->team_id = $request->team_id;
            $user->floor = $request->floor;
            $user->row = $request->row;
            $user->seat_number = $request->seat_number;
        } elseif ($user->hasRole('vendor')) {
            $user->nid = $request->nid;
            $user->nid_image = UploadHelper::handleUpload($request->file('nid_image'), 'uploads/vendor/', $user->nid_image);
            $user->trade_licence = UploadHelper::handleUpload($request->file('trade_licence'), 'uploads/vendor/', $user->trade_licence);
            $user->visiting_card = UploadHelper::handleUpload($request->file('visiting_card'), 'uploads/vendor/', $user->visiting_card);
        }

        $user->avater = UploadHelper::handleUpload($request->file('profile_image'), 'uploads/profile/', $user->avater);

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profile updated successfully');
    }
}
