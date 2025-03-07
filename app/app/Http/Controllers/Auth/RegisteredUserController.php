<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\GeoHelper;
use App\Helpers\HtmlHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\NevebounceRule;
use App\Rules\ReCaptcha;
use App\Services\BeeHiivService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'recaptcha_response' => ['required', new ReCaptcha()],
            'email' => ['required', 'lowercase', 'email', 'max:100', 'unique:' . User::class, new NevebounceRule()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'disclaimer' => ['accepted'],
            'child_age' => ['required', 'string'],
            'postcode' => ['required', 'string'],
        ]);
        $geoPayload = GeoHelper::getGeoPayload();

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'uuid' => $request->get('fingerprint', Str::uuid()),
            'password' => Hash::make($request->password),
            'country' => $geoPayload['country'],
            'state' => $geoPayload['state'],
        ]);

        $user->saveInfo($request->only(['child_age', 'postcode']));

        try {
            $ogPayload = array_merge( $request->except(['recaptcha_response', 'password', 'password_confirmation', 'fingerprint']), $geoPayload);
            $ogPayload['registration_path'] = str_replace(url('/').'/', '', url()->previous());
            $beeHiivService = new BeeHiivService();
            $beeHiivService->addContact($ogPayload);
        } catch (\Throwable $exception) {
            Log::error('[OG Registration] ' . $exception->getMessage());
        }


        event(new Registered($user));

        Auth::login($user);
        if ($request->has('backto')) {
            Session::put('url.backto', $request->get('backto'));
        }

        return Inertia::location(Session::pull('url.intended', route('ty',  $request->only(HtmlHelper::TRACKING_PARAMS), absolute: false)));
    }
}
