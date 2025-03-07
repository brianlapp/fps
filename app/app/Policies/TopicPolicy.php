<?php

namespace App\Policies;

use App\Models\Topic;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class TopicPolicy
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
     * Determine whether the user can view the Topic.
     */
    public function view(Admin $admin, Topic $topic): bool
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
     * Determine whether the user can update the Topic.
     */
    public function update(Admin $admin, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the Topic.
     */
    public function delete(Admin $admin, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the Topic.
     */
    public function restore(Admin $admin, Topic $topic): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the Topic.
     */
    public function forceDelete(Admin $admin, Topic $topic): bool
    {
        return false;
    }
}
