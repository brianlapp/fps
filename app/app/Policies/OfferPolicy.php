<?php

namespace App\Policies;

use App\Models\Offer;
use App\Models\Admin;

class OfferPolicy
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
     * Determine whether the user can view the Offer list.
     */
    public function viewAll(Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the Offer.
     */
    public function view(Admin $admin, Offer $offer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the Offer.
     */
    public function update(Admin $admin, Offer $offer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the Offer.
     */
    public function delete(Admin $admin, Offer $offer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the Offer.
     */
    public function restore(Admin $admin, Offer $offer): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the Offer.
     */
    public function forceDelete(Admin $admin, Offer $offer): bool
    {
        return true;
    }
}
