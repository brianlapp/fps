<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCategoryPublic;
use App\Http\Resources\ProductPublic;
use App\Models\Product;
use App\Models\ProductCategory;
use Inertia\Inertia;

class ProductController
{
    public function show(string $categorySlug, ?string $slug = null)
    {
        $product = Product::query()->where('slug', $slug ?? $categorySlug)->active()->first();

        if (empty($product)) {
            abort(404);
        }

        $category = !empty($slug) ?
            ProductCategory::query()->where('slug', $categorySlug)->active()->first() :
            $product->categories()->first();

        $relatedProducts = $category->products()
            ->active()
            ->where('products.id', '!=', $product->id)->inRandomOrder()->limit(3)->get();

        return Inertia::render('Products/Product', [
            'product' => new ProductPublic($product),
            'category' => new ProductCategoryPublic($category),
            'relatedProducts' => ProductPublic::collection($relatedProducts)
        ])->withViewData(['meta' => [
            'title' => $product->seo_title ?? $product->title,
            'headline' => $product->headline,
            'link' => route('product_categories.showProduct', $product->slug),
            'image' => $product->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $product->seo_title ?? $product->title,
                'image' => [
                    $product->getFirstMediaUrl('image', 'webp')
                ],
                'datePublished' => $product->updated_at->toIsoString(),
            ]
        ]]);
    }
}
