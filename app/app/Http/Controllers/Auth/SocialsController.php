<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GeoHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BeeHiivService;
use Carbon\Carbon;
use Google\Rpc\Context\AttributeContext\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class SocialsController extends Controller
{
    public function redirect(string $provider)
    {
        // We have to pass UTM and other GET params to state to get them available in the callback
        $redirect = Socialite::driver($provider)->redirect();
        $parsedUrl = parse_url($redirect->getTargetUrl());
        parse_str($parsedUrl['query'], $query);
        $query['state'] = $query['state'] . '|' . json_encode(request()->except('fingerprint')) . '|';
        $queryString = http_build_query($query);
        $newUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . $parsedUrl['path'] . '?' . $queryString;
        $redirect->setTargetUrl($newUrl);

        return $redirect;
    }

    public function callback(string $provider)
    {
        return $this->providerCallback($provider, request()->get('credential'));
    }

    private function providerCallback(string $provider, ?string $token)
    {
        $idField = match ($provider) {
            'linkedin-openid' => 'linkedin_id',
            'google-one-tap' => 'google_id',
            default => $provider . '_id',
        };
        try {
            if (preg_match('/^[^|]+\|([^|]+)/', request()->get('state'), $matches)) {
                $utmParams = json_decode($matches[1], true);
                request()->merge(['state' => str_replace([$matches['1'], '|'], ['', ''], request()->get('state'))]);
            } else {
                $utmParams = [];
            }
        } catch (\Throwable $exception) {
            $utmParams = [];
            Log::error('UTM parsing error: ' . $exception->getMessage() . '. State: ' . request()->get('state'));
        }
        $utmParams['reg'] = 1;

        try {
            $user = !empty($token) ? Socialite::driver($provider)->stateless()->userFromToken($token) : Socialite::driver($provider)->user();
            $current_user = User::where($idField, $user->id)->first();

            if ($current_user) {

                Auth::login($current_user);

                if (isset($utmParams['backto'])) {
                    Session::put('url.backto', $utmParams['backto']);
                    unset($utmParams['backto']);
                }
                return redirect()->intended(route('ty', $utmParams));

            } else {
                $geoPayload = GeoHelper::getGeoPayload();
                $newUser = User::updateOrCreate(['email' => $user->getEmail(), 'uuid' => request()->get('fingerprint', Str::uuid()),], [
                    $idField => $user->id,
                ]);

                $firstName = $user->getName();
                $lastName = null;
                try {
                    $n = explode(' ', $user->getName());

                    if (isset($n[0])) {
                        $firstName = $n[0];
                    }
                    if (isset($n[1])) {
                        $lastName = $n[1];
                    }
                } catch (\Throwable $exception) {
                    Log::error('[providerCallback.nameParsing] ' . $exception->getMessage());
                }

                if (empty($newUser->first_name)) {
                    $newUser->first_name = $firstName;
                }

                if (empty($newUser->last_name)) {
                    $newUser->last_name = $lastName;
                }

                if (empty($newUser->avatar)) {
                    $newUser->avatar = $user->getAvatar() ?? null;
                }

                if (empty($newUser->email_verified_at)) {
                    $newUser->email_verified_at = Carbon::now('UTC');
                }
                if (empty($newUser->country)) {
                    $newUser->country = $geoPayload['country'];
                    $newUser->state = $geoPayload['state'];
                }
                $newUser->save();

                try {
                    $ogPayload = array_merge(
                        $newUser->only(['email', 'first_name', 'last_name']),
                        $utmParams,
                        $geoPayload
                    );
                    $beeHiivService = new BeeHiivService();
                    $beeHiivService->addContact($ogPayload);
                } catch (\Throwable $exception) {
                    Log::error('[OG Registration Social] ' . $exception->getMessage());
                }

                Auth::login($newUser);

                if (isset($utmParams['backto'])) {
                    Session::put('url.backto', $utmParams['backto']);
                    unset($utmParams['backto']);
                }
                return redirect()->intended(route('ty', $utmParams));
            }

        } catch (\Throwable $e) {
            Log::error('[ProviderCallback ' . $provider . ']: ' . $e->getMessage());

            return redirect(route('signup'))->with(['error' => 'Something went Wrong!']);
        }
    }
}

