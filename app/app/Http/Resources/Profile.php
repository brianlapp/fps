<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'uuid' => $this->uuid,
            'prompt_attribution' => $this->prompt_attribution,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'slug' => $this->slug,
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumbnail'),
            'fullAvatar' => $this->getFirstMediaUrl('avatar', 'webp'),
            'bio' => $this->bio
        ];
    }
}
