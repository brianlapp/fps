<?php

namespace App\Http\Controllers\Admin;

use App\Models\SiteConfig;
use App\Http\Controllers\Controller;

class SiteConfigController extends Controller
{
    public function update(\Illuminate\Http\Request $request)
    {
        $key = $request->get('key');

        $record = SiteConfig::where('key', $key)->first();

        if (!$record) {
            $record = new SiteConfig(['key' => $key]);
        }

        $record->value = $request->get('settings');

        $record->save();

        return back()->with('success', 'Settings Updated!');
    }

}
