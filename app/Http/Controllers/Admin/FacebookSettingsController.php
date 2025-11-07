<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FacebookSetting;

class FacebookSettingsController extends Controller
{
    public function index()
    {
        $settings = FacebookSetting::first();
        return view('admin.facebook_settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'page_access_token' => 'nullable|string',
            'page_id' => 'nullable|string',
            'page_username' => 'nullable|string',
            'admin_psid' => 'nullable|string',
            'verify_token' => 'nullable|string',
        ]);

        $settings = FacebookSetting::first();
        if (! $settings) {
            $settings = FacebookSetting::create($data);
        } else {
            $settings->update($data);
        }

        return redirect()->back()->with('success', 'Facebook settings saved.');
    }
}
