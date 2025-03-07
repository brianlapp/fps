<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\Admin;
use \App\Http\Resources\Admin\Admin as AdminResource;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        /**
         * @var $admin Admin
         */
        $adminModel = Auth::guard('admin')->user();
        return Inertia::render('Admin/Profile/Edit', [
            'mustVerifyEmail' => $adminModel instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => new AdminResource($adminModel)
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /**
         * @var $admin Admin
         */
        $admin = Auth::guard('admin')->user();
        $admin->fill($request->validated());

        if ($admin->isDirty('email')) {
            $admin->email_verified_at = null;
        }

        $admin->save();

        return Redirect::route('admin.profile.edit')->with('message', 'Profile Updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password:admin'],
        ]);
        /**
         * @var $admin Admin
         */
        $admin = Auth::guard('admin')->user();

        Auth::logout();

        $admin->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function updateAvatar(Request $request): RedirectResponse
    {
        try {
            /**
             * @var $admin Admin
             */
            $admin = Auth::guard('admin')->user();
            $admin->addMediaFromBase64($request->get('avatar'))->toMediaCollection('avatar');
            return Redirect::route('admin.profile.edit');
        } catch (\Throwable $exception) {
            Log::error('[updateAvatar]: ' . $exception->getMessage());
            return Redirect::route('admin.profile.edit')->with(['error' => __('common.something_wrong')]);
        }

    }
}
