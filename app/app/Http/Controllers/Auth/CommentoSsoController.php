<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\HashHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CommentoSsoController extends Controller
{
    /**
     * Generate SSO payload for Commento.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sso(Request $request): mixed
    {
        $user = $request->user();
        if (empty($user)) {
            Session::put('url.intended', route('commento-sso') . '?' . http_build_query($request->only(['hmac', 'token'])));
            return Redirect::route('signup');
        }
        // Replace this with the actual secret key from the pseudocode
        $secretKey = HashHelper::hexDecode(config('services.commento.sso_secret'));

        // Get 'token' and 'hmac' from the query parameters
        $token = $request->get('token');
        $hmac = HashHelper::hexDecode($request->get('hmac', ''));

        // Calculate the expected HMAC
        $expectedHmac = HashHelper::hmacSha256(HashHelper::hexDecode($token), $secretKey);

        // Validate HMAC
        if (!hash_equals($hmac, $expectedHmac)) {
            // Discard and terminate if HMAC does not match
            return Redirect::back(400)->with(['error' => __('common.something_wrong')]);
        }


        // Prepare the payload as per Commento's requirements
        $payload = json_encode([
            'token' => $token,
            'id' => (string)$user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'link' => route('prompt_author.show', $user->slug),
            'photo' => $user->getFirstMediaUrl('avatar', 'thumbnail')
            // Add more fields if needed
        ]);

        // Calculate the HMAC for the payload
        $payloadHmac = HashHelper::hexEncode(HashHelper::hmacSha256($payload, $secretKey));

        // Encode the payload as a hex string
        $payloadHex = HashHelper::hexEncode($payload);


        $url = "https://comments.freeparentsearch.com/api/oauth/sso/callback?payload={$payloadHex}&hmac={$payloadHmac}";

        return redirect($url);
    }
}
