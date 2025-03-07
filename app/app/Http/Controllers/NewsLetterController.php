<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Helpers\HtmlHelper;
use App\Http\Requests\GiveAwayRequest;
use App\Http\Requests\NewsLetterRequest;
use App\Services\BeeHiivService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use App\Services\DataReportingService;

class NewsLetterController extends Controller
{
    public function join(NewsLetterRequest $request)
    {
        $payload = array_merge($request->except(['recaptcha_response', 'fingerprint']), GeoHelper::getGeoPayload());
        $payload['ip'] = GeoHelper::getIp();
        $payload['registration_type'] = 'email';

        $beeHiiv = new BeeHiivService();
        $beeHiiv->addContact($payload, false);

        if (isset($payload['name'])) {
            $payload['first_name'] = '';
            $payload['last_name'] = '';
            try {
                $n = explode(' ', $payload['name']);

                if (isset($n[0])) {
                    $request->merge(['first_name' => $n[0]]);
                }
                if (isset($n[1])) {
                    $request->merge(['last_name' => $n[1]]);
                }
            } catch (\Throwable $exception) {
                Log::error('[NewsLetterController.nameParsing] ' . $exception->getMessage());
                $request->merge(['first_name' => $payload['name']]);
            }
        }

        if ($request->has('backto')) {
            Session::put('url.backto', $request->get('backto'));
        }

        return Redirect::route('signup',  $request->only(array_merge(['email', 'first_name', 'last_name'], HtmlHelper::TRACKING_PARAMS)));
    }
}
