<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use App\Models\Traits\HasSingleImage;

class Topic extends Model implements HasMedia
{
    use SoftDeletes,
        Sluggable,
        HasSingleImage;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_active'
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
        });
    }

    /**
     * Get softDeleted topics.
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

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function getHeadlineAttribute(): string
    {
        try {
            preg_match('/<p>(.*?)<\/p>/s', $this->description, $match);
            return $match[1];
        } catch (\Throwable $exception) {
            return '';
        }
    }
}
