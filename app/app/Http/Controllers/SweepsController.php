<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Helpers\HtmlHelper;
use App\Http\Requests\GiveAwayRequest;
use App\Http\Requests\SweepRequest;
use App\Models\User;
use App\Services\BeeHiivService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Services\DataReportingService;

class SweepsController extends Controller
{
    public function index(Request $request)
    {
    }
    public function submit(SweepRequest $request)
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
                $ogPayload = array_merge( $request->except(['recaptcha_response', 'password', 'password_confirmation', 'fingerprint', 'prize']), $geoPayload);
                $ogPayload['registration_path'] = str_replace(url('/').'/', '', url()->previous());

                $beeHiivService = new BeeHiivService();
                $beeHiivService->addContact($ogPayload);
            } catch (\Throwable $exception) {
                Log::error('[OG Registration on Sweeps Submit] ' . $exception->getMessage());
            }
        }
        if (empty(Auth::user())) {
            Auth::login($user);
        }
        $user->saveInfo($request->only(['child_age', 'postcode']));

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

        $beeHiiv = new BeeHiivService();
        $beeHiiv->updateContact($user->email, $payload);
        $beeHiiv->addTags($user->email, [$request->get('prize')]);

        $query = $request->only(HtmlHelper::TRACKING_PARAMS);

        return redirect(route('ty', $query));
    }
}
