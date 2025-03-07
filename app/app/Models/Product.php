<?php

namespace App\Models;

use App\Models\Traits\HasSingleImage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class Product extends Model implements HasMedia
{
    use SoftDeletes,
        Sluggable,
        HasSingleImage;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'long_description',
        'seo_title',
        'seo_description',
        'affiliate_links',
        'deals',
        'price_from',
        'price_to',
        'price_range_string',
        'order',
        'is_active',
    ];

    protected $casts = [
        'affiliate_links' => 'array',
        'is_active' => 'boolean',
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
                'source' => 'title',
                'onUpdate' => true
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
        })->when($filters['categories'] ?? null, function ($query, $categories) {
            $query->whereHas('categories', function ($categoriesQuery) use ($categories) {
                $categoriesQuery->whereIn('product_categories.id', $categories);
            });
        });
    }

    /**
     * Get softDeleted Products.
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

    /**
     * The categories that belong to the product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class, 'products_to_product_categories', 'product_id', 'product_categories_id');
    }

    public function getHeadlineAttribute(): string
    {
        if (!empty($this->seo_description)) {
            return $this->seo_description;
        }
        try {
            return html_entity_decode(strip_tags($this->description));
        } catch (\Throwable $exception) {
            return '';
        }
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function calculateRating(): void
    {
        $this->rating = number_format($this->reviews()->approved()->avg('rate'), 1) ?? 0;
        $this->reviews_count = $this->reviews()->approved()->count() ?? 0;
        $this->saveQuietly();
    }
}

