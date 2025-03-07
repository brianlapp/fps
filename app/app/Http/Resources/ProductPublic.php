<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductPublic extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $defaultImage = url('/images/product_placeholder.png');
        return [
            'title' => $this->title,
            'description' => $this->description,
            'long_description' => $this->long_description,
            'seo_title' => $this->seo_title ?? $this->title,
            'headline' => $this->headline,
            'slug' => $this->slug,
            'deals' => $this->deals,
            'price_from' => $this->price_from,
            'price_to' => $this->price_to,
            'price_range_string' => $this->price_range_string,
            'affiliate_links' => $this->affiliate_links,
            'image' => $this->getFirstMediaUrl('image', 'webp') ?: $defaultImage,
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail') ?: $defaultImage,
            'categories' => ProductCategoryPublic::collection($this->categories),
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count
        ];
    }
}

