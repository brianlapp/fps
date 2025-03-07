<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ( Auth::guard('admin')->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        }

        if ( Auth::guard('admin')->user()->markEmailAsVerified()) {
            event(new Verified( Auth::guard('admin')->user()));
        }

        return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
    }
}
