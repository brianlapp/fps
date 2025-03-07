<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlannerCategoryPublic extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        return [
            'title' => $this->title,
            'description' => $this->description,
            'color' => $this->color,
            'slug' => $this->slug,
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
            'sections' => PlannerSectionPublic::collection($this->plannerSections),
            'links' => $this->links,
            'show_image' => $this->show_image,
            'progress' => $user?->getPlannerProgress($this->id) ?? 0
        ];
    }
}
