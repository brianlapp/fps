<?php

namespace App\Policies;

use App\Models\PlannerCategory;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class PlannerCategoryPolicy
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

    /**
     * Determine whether the user can view the PlannerCategories list.
     */
    public function viewAll(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the PlannerCategory.
     */
    public function view(Admin $admin, PlannerCategory $plannerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the PlannerCategory.
     */
    public function update(Admin $admin, PlannerCategory $plannerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the PlannerCategory.
     */
    public function delete(Admin $admin, PlannerCategory $plannerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the PlannerCategory.
     */
    public function restore(Admin $admin, PlannerCategory $plannerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the PlannerCategory.
     */
    public function forceDelete(Admin $admin, PlannerCategory $plannerCategory): bool
    {
        return false;
    }
}
