<?php

namespace App\Models;

use App\Models\Traits\HasSingleImage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Offer extends Model implements HasMedia
{
    use SoftDeletes,
        HasSingleImage,
        Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'link',
        'description',
        'is_active',
        'offer_category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * Filtering table.
     *
     * @param $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['isActive'] ?? null, function ($query, $isActive) {
            if ($isActive === 'active') {
                $query->active();
            } elseif ($isActive === 'inactive') {
                $query->inactive();
            }
        })->when($filters['countries'] ?? null, function ($query, $countries) {
            $query->countries($countries);
        })->when($filters['country'] ?? null, function ($query, $country) {
            if ($country !== 'all') {
                $query->country($country);
            }
        });
    }

    /**
     * Get softDeleted articles.
     *
     * @param mixed $value
     * @param null $field
     * @return Model|null
     */
    public function resolveRouteBinding($value, $field = null): ?Model
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query): void
    {
        $query->where('is_active', false);
    }

    public function offerCategory(): BelongsTo
    {
        return $this->belongsTo(OfferCategory::class);
    }

    public function countries(): BelongsToMany
    {
        return $this->belongsToMany(Country::class);
    }

    public function scopeCountry(Builder $query, string $countryCode): void
    {
        $query->whereHas('countries', function (Builder $q) use ($countryCode) {
            $q->where('code', $countryCode);
        });
    }

    public function scopeCountries(Builder $query, array $countryIds): void
    {
        $query->whereHas('countries', function (Builder $q) use ($countryIds) {
            $q->whereIn('countries.id', $countryIds);
        });
    }
}
