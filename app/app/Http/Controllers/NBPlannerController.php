<?php

namespace App\Http\Controllers;

use App\Http\Resources\OfferCategoryPublic;
use App\Http\Resources\ArticlePublic;
use App\Http\Resources\OfferPublic;
use App\Http\Resources\PlannerCategoryPublic;
use App\Models\Article;
use App\Models\ArticleRequest;
use App\Models\Offer;
use App\Models\OfferCategory;
use App\Models\PlannerCategory;
use App\Models\PlannerItem;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;

class NBPlannerController
{
    public function show(string $slug, Request $request)
    {
        $user = Auth::user();
        /**
         * @var $categoryModel PlannerCategory
         */
        $categoryModel = PlannerCategory::query()->where('slug', $slug)->first();

        if (empty($categoryModel)) {
            abort(404);
        }

        $category = new PlannerCategoryPublic($categoryModel);

        $allCategories = PlannerCategory::query()->orderBy('order', 'ASC')->get()->transform(fn($item) => [
            'title' => $item->title,
            'slug' => $item->slug,
            'link' => route('planner_categories.show', $item->slug),
            'allowed_for_guest' => true,
            'has_sections' => $item->plannerSections()->count() > 0,
            'is_current' => $item->slug === $slug
        ])->toArray();
        $next = null;
        $allowedForGuest = false;
        foreach ($allCategories as $index => $item) {
            if ($item['has_sections'] && $index > 1) {
                $allCategories[$index]['allowed_for_guest'] = false;
            }
            if ($item['is_current']) {
                if (isset($allCategories[$index + 1])) {
                    $next = $allCategories[$index + 1];
                }
                if (!$item['has_sections'] || $index === 1) {
                    $allowedForGuest = true;
                }
            }
        }

        if (empty($user) && !$allowedForGuest) {
            return Redirect::route('signup')->with(['message' => 'Please Signup to proceed with Ultimate Checklist!']);
        }

        $progress = $user?->getPlannerProgress() ?? 0;
        $checkedItems = $user?->plannerItems()->select('uuid')->pluck('uuid') ?? [];

        return Inertia::render('NbPlanner/Page', compact('allCategories', 'category', 'next','progress', 'checkedItems'))->withViewData(['meta' => [
            'title' => 'Ultimate Baby Checklist & Planner: Prepare for Your Newborn',
            'headline' => 'Get Ready for Your Baby with the Ultimate Baby Checklist & Planner',
            'link' => route('planner_categories.index'),
            'image' => $categoryModel->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => 'Ultimate Baby Checklist & Planner: Prepare for Your Newborn',
                'image' => [
                    $categoryModel->getFirstMediaUrl('image', 'webp')
                ],
                'datePublished' => $categoryModel->updated_at->toIsoString(),
            ]
        ], 'noSidebar' => true]);
    }

    public function toggleItem(string $uuid)
    {
        $user = Auth::user();
        try {
            $item = PlannerItem::query()->where('uuid', $uuid)->first();
            if (empty($item)) {
                return response()->json([
                    'value' => false,
                    'progress' => $user->getPlannerProgress(),
                    'categoryProgress' => null
                ]);
            }
            $value = !empty($user->plannerItems()->toggle([$item->id])['attached']);

            return response()->json([
                'value' => $value,
                'progress' => $user->getPlannerProgress(),
                'categoryProgress' => $user->getPlannerProgress($item->plannerSection->planner_category_id),
            ]);
        } catch (\Throwable $exception) {
            Log::error('[toggleItem] ' . $exception->getMessage());

            return response()->json([
                'value' => false,
                'progress' => $user->getPlannerProgress()
            ]);
        }
    }
}
