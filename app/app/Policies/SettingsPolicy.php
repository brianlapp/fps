<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\User;

class SettingsPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(Admin $admin, string $ability): bool|null
    {
        if ($admin->is_super || $admin->role_string === 'admin') {
            return true;
        }

        return null;
    }

    public function viewSettings(Admin $admin): bool
    {
        return $admin->isAdmin();
    }

    public function editMenu(Admin $admin): bool
    {
        return $admin->isAdmin();
    }
}
