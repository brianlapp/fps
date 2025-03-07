<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SiteConfig extends Model implements HasMedia
{
    use InteractsWithMedia;
    protected $table = 'site_config';

    const CODE_INJECTIONS_CACHE_KEY = 'code_injections';
    const SOCIALS_CACHE_KEY = 'socials';
    /**
     * Fields from DB.
     *
     * @var string[]
     */
    protected $fillable = [
        'value', 'key'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'value' => 'json'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->getKeyName();
    }

    /**
     * Search config item by key
     * @param $key
     * @return array|null
     */
    public static function byKey($key): ?array
    {
        $record = self::where('key', $key)->first();
        $value = $record ? $record->value : null;
        if (!empty($value) && $key == 'social_settings') {
            $value['seo']['image'] = $record->getFirstMediaUrl('seo_image', 'webp') ?: null;
        }

        return $value;
    }

    public static function createByKey($key, $defaultValue)
    {
        return self::create([
            'key' => $key,
            'value' => $defaultValue
        ]);
    }

    public static function updateByKey($key, $value)
    {
        $record = self::where('key', $key)->first();
        if ($key == 'social_settings') {
            $imageKey = 'seo_image';
            $image = $value['seo']['image'];
            unset($value['seo']['image']);
        }
        $record->value = $value;
        $record->save();

        if (isset($imageKey)) {
            $record->updateImage($image, $imageKey);
        }
    }

    /**
     * Get or Create cached Code Injections.
     *
     * @return array
     */
    public static function getCachedCodeInjections(): array
    {

        try {
            if (!Cache::has(self::CODE_INJECTIONS_CACHE_KEY)) {
                Cache::rememberForever(self::CODE_INJECTIONS_CACHE_KEY, function () {

                    return SiteConfig::byKey('injections') ?? [];
                });
            }

            return Cache::get(self::CODE_INJECTIONS_CACHE_KEY) ?? [];

        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('[getCachedCodeInjections] ' . $exception->getMessage());

            return [];
        }
    }

    /**
     * Get or Create cached SEO & Socials Settings.
     *
     * @return array
     */
    public static function getCachedSocials(): array
    {

        try {
            if (!Cache::has(self::SOCIALS_CACHE_KEY)) {
                Cache::rememberForever(self::SOCIALS_CACHE_KEY, function () {

                    return SiteConfig::byKey('social_settings') ?? [];
                });
            }

            return Cache::get(self::SOCIALS_CACHE_KEY) ?? [];

        } catch (\Throwable $exception) {
            \Illuminate\Support\Facades\Log::error('[getCachedSocials] ' . $exception->getMessage());

            return [];
        }
    }



    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->width(400)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('webp')
            ->quality(80)->format('webp')
            ->width(1200)
            ->withResponsiveImages()
            ->nonQueued();;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('seo_image')->singleFile();
    }

    /**
     * @param string|null $image
     * @return void
     */
    public function updateImage(?string $image, $key = 'seo_image'): void
    {
        try {
            if (empty($image)) {
                $this->clearMediaCollection($key);
            } else if (!Str::startsWith($image, ['http'])) {
                $this->addMediaFromBase64($image)
                    ->withResponsiveImages()
                    ->toMediaCollection($key);
            }
        } catch (\Throwable $exception) {
            Log::error('[SiteConfig Image Update]: ' . $exception->getMessage());
        }

    }
}
