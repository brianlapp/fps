<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class ProductPolicy
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
     * Determine whether the user can view the Products list.
     */
    public function viewAll(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the Product.
     */
    public function view(Admin $admin, Product $product): bool
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
     * Determine whether the user can update the Product.
     */
    public function update(Admin $admin, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Product.
     */
    public function delete(Admin $admin, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the Product.
     */
    public function restore(Admin $admin, Product $product): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the Product.
     */
    public function forceDelete(Admin $admin, Product $product): bool
    {
        return false;
    }
}
