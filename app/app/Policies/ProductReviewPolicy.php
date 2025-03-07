<?php

namespace App\Policies;

use App\Models\ProductReview;
use App\Models\Admin;

class ProductReviewPolicy
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
     * Determine whether the user can view the ProductReviews list.
     */
    public function viewAll(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the ProductReview.
     */
    public function view(Admin $admin, ProductReview $productReview): bool
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
     * Determine whether the user can update the ProductReview.
     */
    public function update(Admin $admin, ProductReview $productReview): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the ProductReview.
     */
    public function delete(Admin $admin, ProductReview $productReview): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the ProductReview.
     */
    public function restore(Admin $admin, ProductReview $productReview): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the ProductReview.
     */
    public function forceDelete(Admin $admin, ProductReview $productReview): bool
    {
        return false;
    }
}
