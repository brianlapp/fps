<?php

namespace App\Models;

use App\Http\Models\AiDocument;
use App\Models\Traits\HasSingleImage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;

class PlannerCategory extends Model implements HasMedia
{
    use SoftDeletes,
        HasSingleImage,
        Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'color',
        'description',
        'items',
        'links',
        'as_article',
        'show_image'
    ];

    protected $casts = [
        'as_article' => 'boolean',
        'show_image' => 'boolean',
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
            ],
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

    public function plannerSections(): HasMany
    {
        return $this->hasMany(PlannerSection::class)->orderBy('order', 'ASC');
    }

    public function getHeadlineAttribute(): string
    {
        try {
            preg_match('/<p>(.*?)<\/p>/s', $this->description, $match);
            return html_entity_decode(strip_tags($match[1]));
        } catch (\Throwable $exception) {
            return '';
        }
    }

    public function referencedArticle(): MorphOne
    {
        return $this->morphOne(Article::class, 'entity')->withReferences();
    }

    public function getLink(): string
    {
        return route('planner_categories.show', $this->slug);
    }
}
