<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Product extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $admin = Auth::guard('admin')->user();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'long_description' => $this->long_description,
            'seo_title' => $this->seo_title,
            'seo_description' => $this->seo_description,
            'slug' => $this->slug,
            'deals' => $this->deals,
            'price_from' => $this->price_from,
            'price_to' => $this->price_to,
            'price_range_string' => $this->price_range_string,
            'affiliate_links' => $this->affiliate_links,
            'is_active' => $this->is_active,
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
            'categories' => ProductCategory::collection($this->categories),
            'deleted_at' => $this->deleted_at,
            'can' => [
                'edit' => $admin->can('update', $this),
                'delete' => $admin->can('delete', $this),
            ],
            'rating' => $this->rating,
            'reviews_count' => $this->reviews_count
        ];
    }
}

