<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductCategoryPublic extends JsonResource
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
            'seo_title' => $this->seo_title ?? $this->title,
            'headline' => $this->headline,
            'slug' => $this->slug,
            'image' => $this->getFirstMediaUrl('image', 'webp') ?: $defaultImage,
            'imageSrcSet' =>  $this->getFirstMedia('image')?->getSrcset('webp') ?? null,
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail') ?: $defaultImage,
        ];
    }
}
