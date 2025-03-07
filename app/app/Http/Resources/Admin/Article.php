<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class Article extends JsonResource
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
            'uuid' => $this->uuid,
            'title' => $this->title,
            'slug' => $this->slug,
            'status' => $this->status,
            'content' => $this->content,
            'author' => new AdminShort($this->author),
            'tags' => $this->tags->transform(function ($tag) {
                return $tag->name;
            }),
            'published_at' => $this->published_at,
            'can' => [
                'edit' => $admin->can('update', $this),
                'delete' => $admin->can('delete', $this),
            ],
            'image' => $this->getFirstMediaUrl('image', 'webp'),
            'thumbnail' => $this->getFirstMediaUrl('image', 'thumbnail'),
            'is_page' => $this->is_page,
            'image_caption' => $this->image_caption,
            'topics' => Topic::collection($this->topics),
            'was_improved' => $this->was_improved,
            'seo_title' => $this->seo_title,
            'seo_headline' => $this->seo_headline,
            'is_featured' => $this->is_featured,
        ];
    }
}
