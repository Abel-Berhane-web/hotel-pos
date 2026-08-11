<?php

namespace App\Http\Controllers;

use App\Models\BusinessSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [];
        foreach (['business_name_en', 'business_name_am', 'business_phone', 'currency', 'tax_rate', 'low_stock_threshold'] as $key) {
            $settings[$key] = BusinessSetting::get($key, '');
        }
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'business_name_en' => 'required|string|max:255',
            'business_name_am' => 'required|string|max:255',
            'business_phone' => 'nullable|string|max:20',
            'currency' => 'required|string|max:10',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        foreach (['business_name_en', 'business_name_am', 'business_phone', 'currency', 'tax_rate', 'low_stock_threshold'] as $key) {
            BusinessSetting::set($key, $request->$key);
        }

        return redirect()->route('settings.index')->with('success', __('m.settings_saved'));
    }
}
