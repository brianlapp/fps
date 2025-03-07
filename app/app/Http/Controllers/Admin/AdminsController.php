<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ProfileCreateRequest;
use App\Http\Requests\Admin\ProfileUpdateRequest;
use App\Models\Admin;
use Google\Service\Dfareporting\Ad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\Admin as AdminResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class AdminsController
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'role']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = Admin::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(AdminResource::collection($list->getCollection())->collection);
        $roles = Admin::ROLES;

        return Inertia::render('Admin/Admins/Index', compact('list', 'filters', 'roles'));
    }

    public function create(Request $request): Response
    {
        $roles = Admin::ROLES;

        return Inertia::render('Admin/Admins/Edit', [
            'roles' => $roles
        ]);
    }

    public function store(ProfileCreateRequest $request): RedirectResponse
    {
        $admin = new Admin();
        $admin->fill($request->validated());
        $admin->password = Hash::make($request->get('password'));
        $admin->save();

        return Redirect::route('admin.team.edit', $admin->id)->with('message', 'Profile Created');
    }

    public function edit(Admin $admin, Request $request): Response
    {
        $roles = Admin::ROLES;

        return Inertia::render('Admin/Admins/Edit', [
            'user' => new AdminResource($admin),
            'roles' => $roles
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Admin $admin, ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($request->has('role') && in_array($request->get('role'), array_keys(Admin::ROLES))) {
            $data['role'] = $request->get('role');
        }
        $admin->fill($data);
        $admin->save();

        return Redirect::route('admin.team.index')->with('message', 'Profile Updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Admin $admin, Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password:admin'],
        ]);

        $admin->delete();

        return Redirect::route('admin.team.index')->with('message', 'Profile Deleted');
    }

    public function updateAvatar(Admin $admin, Request $request): RedirectResponse
    {
        try {
            $admin->addMediaFromBase64($request->get('avatar'))
                ->usingFileName(Str::random(24))
                ->toMediaCollection('avatar');
            return Redirect::route('admin.team.edit', $admin->id);
        } catch (\Throwable $exception) {
            Log::error('[team updateAvatar]: ' . $exception->getMessage());
            return Redirect::route('admin.team.edit', $admin->id)->with(['error' => __('common.something_wrong')]);
        }
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Admin $admin, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $admin->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('message', 'Password Updated');
    }
}
