<?php

namespace App\Policies;

use App\Models\PlannerSection;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class PlannerSectionPolicy
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
     * Determine whether the user can view the PlannerSection.
     */
    public function view(Admin $admin, PlannerSection $plannerSection): bool
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
     * Determine whether the user can update the PlannerSection.
     */
    public function update(Admin $admin, PlannerSection $plannerSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the PlannerSection.
     */
    public function delete(Admin $admin, PlannerSection $plannerSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the PlannerSection.
     */
    public function restore(Admin $admin, PlannerSection $plannerSection): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the PlannerSection.
     */
    public function forceDelete(Admin $admin, PlannerSection $plannerSection): bool
    {
        return false;
    }
}
