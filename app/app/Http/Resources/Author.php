<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Author extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avatar = $this->getFirstMediaUrl('avatar', 'webp');
        try {
            $exName = explode(' ', $this->name);
            $firstName = $exName[0];
            $lastName = $exName[1];
        } catch (\Throwable $exception) {
            $firstName = $this->name;
            $lastName = '';
        }
        return [
            'name' => $this->name,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'slug' => $this->slug,
            'email' => $this->email,
            'title' => $this->title,
            'avatar' => $avatar,
            'bio' => $this->long_bio
        ];
    }
}
