<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ClientIdentifier
{
    const KEY = 'fingerprint';

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::getUser();
        $fingerprint = !empty($user) ? $user->uuid : ($request->cookie(self::KEY) ?? Str::uuid()->toString());

        // Refresh the cookie on every visit to extend its lifetime for 5 years
        Cookie::queue(self::KEY, $fingerprint, 60 * 24 * 365 * 5);
        $request->merge([self::KEY => $fingerprint]);

        return $next($request);
    }
}
