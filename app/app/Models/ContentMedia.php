<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ContentMedia extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'content_media';
    protected $guarded = ['id'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->quality(80)->format('webp')
            ->withResponsiveImages()
            ->nonQueued();;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->withResponsiveImages();
    }

    public static function getByEntity(string $entity): ContentMedia
    {
        $payload = [
            'entity' => $entity,
        ];

        return self::firstOrCreate(['entity' => $payload['entity']], $payload);
    }
}
