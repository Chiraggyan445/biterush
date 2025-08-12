<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    protected $file = 'settings.json';

    public function index()
    {
        $settings = [];
        if (Storage::disk('local')->exists($this->file)) {
            $settings = json_decode(Storage::disk('local')->get($this->file), true);
        }

        $settings['razorpay_key'] = env('RAZORPAY_KEY');
        $settings['razorpay_secret'] = env('RAZORPAY_SECRET');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:100',
            'currency' => 'required|string|max:5',
            'razorpay_key' => 'nullable|string|max:100',
            'razorpay_secret' => 'nullable|string|max:100',
            'minimum_order_amount' => 'required|numeric|min:0',
            'delivery_charge' => 'required|numeric|min:0',
            'dark_mode' => 'nullable|boolean',
        ]);

        $validated['dark_mode'] = $request->has('dark_mode');

        Storage::disk('local')->put($this->file, json_encode($validated, JSON_PRETTY_PRINT));

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
