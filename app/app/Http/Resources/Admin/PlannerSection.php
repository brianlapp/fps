<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PlannerSection extends JsonResource
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
            'single_title' => Str::singular($this->title),
            'description' => $this->description,
            'color' => $this->color,
            'slug' => $this->slug,
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
            'deleted_at' => $this->deleted_at,
            'can' => [
                'edit' => $admin->can('update', $this),
                'delete' => $admin->can('delete', $this),
            ],
            'items' => $this->plannerItems->transform(fn($item) => $item->only(['id', 'uuid', 'slug', 'title', 'description', 'color', 'label'])),
            'links' => $this->links,
            'order' => $this->order,
        ];
    }
}
