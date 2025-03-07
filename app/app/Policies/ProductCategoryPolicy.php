<?php

namespace App\Policies;

use App\Models\ProductCategory;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class ProductCategoryPolicy
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
     * Determine whether the user can view the ProductCategories list.
     */
    public function viewAll(Admin $admin): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the ProductCategory.
     */
    public function view(Admin $admin, ProductCategory $productCategory): bool
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
     * Determine whether the user can update the ProductCategory.
     */
    public function update(Admin $admin, ProductCategory $productCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the ProductCategory.
     */
    public function delete(Admin $admin, ProductCategory $productCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the ProductCategory.
     */
    public function restore(Admin $admin, ProductCategory $productCategory): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the ProductCategory.
     */
    public function forceDelete(Admin $admin, ProductCategory $productCategory): bool
    {
        return false;
    }
}
