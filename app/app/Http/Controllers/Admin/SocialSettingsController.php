<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Inertia\Response;

class SocialSettingsController extends Controller
{

    /**
     * @return Response
     */
    public function edit(): Response
    {
        $values = SiteConfig::byKey('social_settings');
        if (is_null($values)) {
            $values = [
                'seo' => [
                    'title' => '',
                    'headline' => '',
                    'image' => null,
                ],
                'networks' => [
                    'youtube' => null,
                    'facebook' => null,
                    'instagram' => null,
                    'twitter' => null,
                    'snapchat' => null,
                ]
            ];
            SiteConfig::createByKey('social_settings', $values);
        }

        return Inertia::render('Admin/SocialSettings/Edit', $values);
    }

    /**
     * @return RedirectResponse
     */
    public function update(): RedirectResponse
    {
        SiteConfig::updateByKey('social_settings', Request::only(['seo', 'networks']));
        Cache::forget(SiteConfig::SOCIALS_CACHE_KEY);

        return Redirect::back()->with('message', 'Settings updated.');
    }
}
