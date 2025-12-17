<?php

namespace App\Http\Controllers;

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
        return view('backend.profile.edit');
    }

    /**
     * Update profile info + password + avatar
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'username' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->extension();
            $file->move(public_path('uploads/profile'), $filename);
            $user->avatar = 'uploads/profile/' . $filename;
        }

        if ($request->current_password && $request->password) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Current password is incorrect!');
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return Redirect::route('settings.profile')->with('success', 'Profile updated successfully');
    }
}
