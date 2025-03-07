<?php

namespace App\Observers;

use App\Models\Admin;
use App\Models\Article;
use App\Models\PlannerCategory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlannerCategoryObserver
{
    /**
     * Handle the PlannerCategory "saved" event.
     */
    public function saved(PlannerCategory $plannerCategory): void
    {
        if ($plannerCategory->isDirty('as_article')) {
            if (!$plannerCategory->as_article && !empty($plannerCategory->referencedArticle)) {
                $plannerCategory->referencedArticle->forceDelete();
            } else if ($plannerCategory->as_article && empty($plannerCategory->referencedArticle)) {
                $article = $plannerCategory->referencedArticle()->create([
                    'created_by' => Auth::guard('admin')->user()?->id ?? Admin::getAiWriter()->id,
                    'type' => Article::TYPES['reference'],
                    'title' => $plannerCategory->title,
                    'content' => $plannerCategory->description,
                    'uuid' => Str::uuid()
                ]);
                $article->publish();
            }
        }
    }
}
