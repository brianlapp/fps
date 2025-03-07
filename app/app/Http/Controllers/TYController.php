<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Helpers\HtmlHelper;
use App\Http\Requests\GiveAwayRequest;
use App\Models\User;
use App\Services\BeeHiivService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\DataReportingService;

class TYController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $drService = resolve(DataReportingService::class);
        $requestData = $request->except(['_token', 'recaptcha_response', 'fingerprint', 'disclaimer']);
        $embarkCheck = $drService->checkEmail($user?->email ?? '', 'embark');
        if (!empty($user) && $user->country == 'CA'
            && (empty($user->userInfo) || (in_array($user->userInfo?->child_age, ['uptothree', 'threeplus', 'pregnantwithkids']) && !$user->userInfo?->embark_consent))
            && $embarkCheck
        ) {
            return Inertia::render('TYEmbark', !empty($user) ? $user->only(['email']) : [])->withViewData(['meta' => [
                'title' => 'Thank You',
            ]]);
        }

        if ($backTo = Session::pull('url.backto')) {
            return \Redirect::to($backTo)->with('message', 'Thanks! Registration is complete');
        }

        $requestData['source_id'] = match (true) {
            $request->get('embark') => 'emb-ty ',
            !$embarkCheck => 'alt-ty',
            default => 'gen-ty',
        };

        $url = resource_path('views/partials/nbbi.html');
        $height = '400';

//        if($request->get('nbbi') || (!empty($user) && !$embarkCheck)) {
//            $url = resource_path('views/partials/nbbi.html');
//            $height = '400';
//            $requestData['source_id'] = 'alt-ty';
//        } else if ($user?->country === 'US'){
//            $url = 'http://ty.recommended-offers.com/ofsukgen';
//            $height = '950';
//        } else {
//            $url = 'http://ty.recommended-offers.com/ofsaugen';
//            $height = '700';
//        }


        $requestData['color'] = '43ced3';
        $requestData['design'] = 'internalV2';
        if (isset($requestData['utm_source'])) {
            $requestData['sub4'] = $requestData['utm_source'];
        }
        if (Str::startsWith($url, ['http'])) {
            $url .= '?' . http_build_query($requestData);
        }

        $content = str_replace(['http:', '?PARAMS'], ['https:', '?' . http_build_query($requestData)], file_get_contents($url));

        return Inertia::render('TY', compact('height', 'content'))->withViewData(['meta' => [
            'title' => 'Thank You',
        ]]);
    }

    public function giveAway(GiveAwayRequest $request)
    {
        $user = Auth::user();
        if (empty($user)) {
            $user = User::query()->where('email', $request->get('email'))->first();
        }
        if (empty($user)) {
            $geoPayload = GeoHelper::getGeoPayload();
            $user = User::create([
                'first_name' => $request->get('first_name'),
                'last_name' => $request->get('last_name'),
                'email' => $request->get('email'),
                'uuid' => $request->get('fingerprint', Str::uuid()),
                'password' => NULL,
                'country' => $geoPayload['country'],
                'state' => $geoPayload['state'],
            ]);

            try {
                $ogPayload = array_merge($request->except(['recaptcha_response', 'password', 'password_confirmation', 'fingerprint']), $geoPayload);
                $ogPayload['registration_path'] = 'sweeps/embark';

                $beeHiivService = new BeeHiivService();
                $beeHiivService->addContact($ogPayload);
            } catch (\Throwable $exception) {
                Log::error('[OG Registration on GiveAway] ' . $exception->getMessage());
            }
        }
        if (empty(Auth::user())) {
            Auth::login($user);
        }
        $oldEmail = $user->email;
        $user->saveInfo($request->only([
            'child_dob',
            'lang',
            'cst_consent',
            'embark_consent',
            'phone',
            'postcode'
        ]));
        $user->email = $request->get('email');
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->saveQuietly();
        $user->refresh();
        $payload = array_merge($user->only(['email', 'first_name', 'last_name']), $user->userInfo->only([
            'child_dob',
            'lang',
            'cst_consent',
            'embark_consent',
            'phone',
            'postcode']), $request->only(['embark_standalone']));
        $payload['project'] = 'fps';
        $payload['signup_url'] = url()->previous();
        $payload['ip'] = GeoHelper::getIp();
        $drService = resolve(DataReportingService::class);
        if ($request->cst_consent && Carbon::make($payload['child_dob'])->diffInMonths(Carbon::now()) < 37) {
            $payload['provider'] = 'csts';
            if ($payload['lang'] === 'fr') {
                $payload['provider'] = 'csts-fr';
            }
            $drService->addLead($payload);
            $payload['cst'] = 'true';
        }

        $payload['was_in_embark_before'] = !$drService->checkEmail($request->get('email'), 'embark');
        if ($request->embark_consent && Carbon::make($payload['child_dob'])->diffInMonths(Carbon::now()) > 0) {
            $payload['provider'] = 'embark';
            if ($payload['lang'] === 'fr') {
                $payload['provider'] = 'embark-fr';
            }
            $drService->addLead($payload);
            $payload['embark'] = 'true';
        }

        $beeHiiv = new BeeHiivService();
        $beeHiiv->updateContact($oldEmail, $payload);

        $query = $request->only(HtmlHelper::TRACKING_PARAMS);
        $query['embark'] = 1;
        return redirect(route('ty', $query));
    }

    public function embarkStandaloneForm()
    {
        $user = Auth::user();
        $props = [
            'email' => $user?->email ?? null,
            'standalone' => true
        ];

        return Inertia::render('TYEmbark', $props)->withViewData(['meta' => [
            'title' => 'Thank You',
        ]]);
    }
}
