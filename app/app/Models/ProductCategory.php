<?php

namespace App\Models;

use App\Models\Traits\HasSingleImage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;

class ProductCategory extends Model implements HasMedia
{
    use SoftDeletes,
        Sluggable,
        HasSingleImage;

    protected $fillable = [
        'title', 'slug', 'description', 'seo_title', 'seo_description', 'order', 'is_active',
    ];

    protected $casts = [
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

    public function scopeActive(Builder $query):void
    {
        $query->where('is_active', true);
    }

    public function scopeInactive(Builder $query):void
    {
        $query->where('is_active', false);
    }

    /**
     * The products that belong to the product category.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'products_to_product_categories', 'product_categories_id', 'product_id');
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
}

