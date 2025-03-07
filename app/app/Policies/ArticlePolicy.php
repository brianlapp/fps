<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\Admin;
use Illuminate\Auth\Access\Response;
use function Symfony\Component\Translation\t;

class ArticlePolicy
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
     * Determine whether the user can view the Article.
     */
    public function view(Admin $admin, Article $article): bool
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
     * Determine whether the user can update the Article.
     */
    public function update(Admin $admin, Article $article): bool
    {
        return $admin->id === $article->created_by || $this->canAccessOtherAuthorArticles($admin);
    }

    /**
     * Determine whether the user can delete the Article.
     */
    public function delete(Admin $admin, Article $article): bool
    {
        return $admin->id === $article->created_by || $this->canAccessOtherAuthorArticles($admin);
    }

    /**
     * Determine whether the user can restore the Article.
     */
    public function restore(Admin $admin, Article $article): bool
    {
        return $admin->id === $article->created_by || $this->canAccessOtherAuthorArticles($admin);
    }

    /**
     * Determine whether the user can permanently delete the Article.
     */
    public function forceDelete(Admin $admin, Article $article): bool
    {
        return $this->canAccessOtherAuthorArticles($admin);
    }

    /**
     * Determine whether the user can update the Article.
     */
    public function updateAuthor(Admin $admin): bool
    {
        return $this->canAccessOtherAuthorArticles($admin);
    }

    private function canAccessOtherAuthorArticles(Admin $admin): bool
    {
        return $admin->is_super || in_array($admin->role_string, [
                'admin',
                'moderator',
            ]);
    }
}
