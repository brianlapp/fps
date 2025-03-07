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

class CodeInjectionsController extends Controller
{

    /**
     * @return Response
     */
    public function edit(): Response
    {
        $values = SiteConfig::byKey('injections');
        if (is_null($values)) {
            $values = [
                'header' => '',
                'footer' => ''
            ];
            SiteConfig::createByKey('injections', $values);
        }

        return Inertia::render('Admin/CodeInjections/Edit', $values);
    }

    /**
     * @return RedirectResponse
     */
    public function update(): RedirectResponse
    {
        SiteConfig::updateByKey('injections', Request::only(['header', 'footer']));
        Cache::forget(SiteConfig::CODE_INJECTIONS_CACHE_KEY);

        return Redirect::back()->with('message', 'Code updated.');
    }
}
