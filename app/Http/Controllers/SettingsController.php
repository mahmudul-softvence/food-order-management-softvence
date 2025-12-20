<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

    public function general()
    {
        $setting = Setting::first();
        return view('backend.settings.general', compact('setting'));
    }

    public function logo()
    {
        $setting = Setting::first();
        return view('backend.settings.logo', compact('setting'));
    }

    public function contact()
    {
        $setting = Setting::first();
        return view('backend.settings.contact', compact('setting'));
    }


    public function update(Request $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $data = $request->validate([
            'site_name' => 'nullable|string|max:255',
            'site_description' => 'nullable|string',
            'footer_text' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'favicon' => 'nullable|image|mimes:png,jpg,jpeg,webp,ico|max:1024',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'twitter' => 'nullable|url',
            'linkedin' => 'nullable|url',
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = UploadHelper::handleUpload($request->file('logo'), 'uploads/settings/', $setting->logo);
        }

        if ($request->hasFile('favicon')) {
            $data['favicon'] = UploadHelper::handleUpload($request->file('favicon'), 'uploads/settings/', $setting->favicon);
        }

        $setting->fill($data)->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
