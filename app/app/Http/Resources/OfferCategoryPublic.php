<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OfferCategoryPublic extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'single_title' => Str::singular($this->title),
            'link' => route('offer_categories.show', $this->slug),
            'description' => $this->description,
            'headline' => $this->headline,
            'slug' => $this->slug,
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'imageSrcSet' => $this->getFirstMedia('image')?->getSrcset('webp') ?? null,
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
        ];
    }
}
