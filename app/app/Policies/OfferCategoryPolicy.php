<?php

namespace App\Policies;

use App\Models\OfferCategory;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class OfferCategoryPolicy
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
     * Determine whether the user can view the OfferCategories list.
     */
    public function viewAll(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the OfferCategory.
     */
    public function view(Admin $admin, OfferCategory $offerCategory): bool
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
     * Determine whether the user can update the OfferCategory.
     */
    public function update(Admin $admin, OfferCategory $offerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the OfferCategory.
     */
    public function delete(Admin $admin, OfferCategory $offerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the OfferCategory.
     */
    public function restore(Admin $admin, OfferCategory $offerCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the OfferCategory.
     */
    public function forceDelete(Admin $admin, OfferCategory $offerCategory): bool
    {
        return false;
    }
}
