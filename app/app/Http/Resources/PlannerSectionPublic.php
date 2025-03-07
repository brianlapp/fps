<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlannerSectionPublic extends JsonResource
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
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
            'items' => $this->plannerItems->transform(fn($item) => $item->only(['uuid', 'uuid', 'slug', 'title', 'description', 'color', 'label'])),
            'links' => $this->links,
        ];
    }
}
