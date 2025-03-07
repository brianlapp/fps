<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromptAuthor extends JsonResource
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
            'id' => $this->uuid,
            'name' => $this->name,
            'slug' => $this->slug,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumbnail'),
            'fullAvatar' => $this->getFirstMediaUrl('avatar', 'webp'),
            'bio' => $this->bio
        ];
    }
}
