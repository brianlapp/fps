<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Admin extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatar = $this->getFirstMediaUrl('avatar', 'thumbnail');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'title' => $this->title,
            'slug' => $this->slug,
            'role' => $this->role_string,
            'role_id' => $this->role,
            'short_bio' => $this->short_bio,
            'long_bio' => $this->long_bio,
            'avatar' => $avatar,
            'fullAvatar' => $this->getFirstMediaUrl('avatar', 'webp'),
            'hasAvatar' => $avatar === \App\Models\Admin::DEFAULT_AVATAR,
        ];
    }
}
