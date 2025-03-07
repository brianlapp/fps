<?php

namespace App\Models;

use App\Models\Traits\HasSingleImage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\HasMedia;

class PlannerSection extends Model implements HasMedia
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
        'planner_category_id'
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

    public function plannerCategory(): BelongsTo
    {
        $this->belongsTo(PlannerCategory::class);
    }

    public function plannerItems(): HasMany
    {
        return $this->hasMany(PlannerItem::class);
    }

    public function setItemsAttribute(array $items):void
    {
        $currentItems = $this->plannerItems;
        $idsToLeave = [];
        foreach ($items as $item) {
            $model = $currentItems->where('id', $item['id'])->first();
            if (empty($model)) {
                $model = new PlannerItem();
                $model->planner_section_id = $this->id;
            }
            $model->fill($item);
            $model->save();
            $idsToLeave[] = $model->id;
        }
        $this->plannerItems()->whereNotIn('id', $idsToLeave)->delete();
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
}
