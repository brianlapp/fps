<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait HasSingleImage
{
    use InteractsWithMedia;

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(360)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('webp')
            ->quality(80)->format('webp')
            ->width(1280)
            ->withResponsiveImages()
            ->nonQueued();;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('image')->singleFile();
    }

    /**
     * @param string|null $image
     * @return void
     */
    public function updateImage(?string $image): void
    {
        try {
            if (empty($image)) {
                $this->clearMediaCollection('image');
            } else if (!Str::startsWith($image, ['http'])) {
                $this->addMediaFromBase64($image)
                    ->withResponsiveImages()
                    ->toMediaCollection('image');
            }
        } catch (\Throwable $exception) {
            Log::error('[Image Update]: ' . $exception->getMessage());
        }

    }
}
