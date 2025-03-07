<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryPublic;
use App\Http\Resources\ProductPublic;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Inertia\Inertia;

class ProductCategoryController
{
    public function index(Request $request)
    {
        $categories = ProductCategory::query()->active()->get();
        $page = [
            'title' => 'Product Reviews',
            'image' => url('/images/product_placeholder.png'),
            'description' => 'Discover and review a wide range of products, from baby gear to parenting apps, courses, and more.'
        ];

        return Inertia::render('Products/Index', [
            'categories' => ProductCategoryPublic::collection($categories),
            'page' => $page,
        ])->withViewData(['meta' => [
            'title' => $page['title'],
            'headline' => $page['description'],
            'link' => route('product_categories.index'),
            'image' => $page['image'],
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $page['title'],
                'image' => [
                    $page['image']
                ],
                'datePublished' => Carbon::now()->toIsoString(),
            ]
        ]]);
    }


    public function show(string $slug, Request $request)
    {
        $categoryModel = ProductCategory::query()->where('slug', $slug)->active()->first();

        if (empty($categoryModel)) {
            abort(404);
        }

        $category = new ProductCategoryPublic($categoryModel);
        /**
         * @var $products LengthAwarePaginator
         */
        $products = $categoryModel->products()->active()->latest()->paginate($request->get('per_page', 21));
        $products->setCollection(ProductPublic::collection($products->getCollection())->collection);

        return Inertia::render('Products/Category', compact('products', 'category'))->withViewData(['meta' => [
            'title' => $categoryModel->seo_title ?? $categoryModel->title,
            'headline' => $categoryModel->headline,
            'link' => route('product_categories.show', $categoryModel->slug),
            'image' => $categoryModel->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $categoryModel->seo_title ?? $categoryModel->title,
                'image' => [
                    $categoryModel->getFirstMediaUrl('image', 'webp')
                ],
                'datePublished' => $categoryModel->updated_at->toIsoString(),
            ]
        ]]);
    }
}
