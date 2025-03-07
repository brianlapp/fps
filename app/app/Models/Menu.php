<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Menu extends Model
{
    use SoftDeletes;

    const CACHE_KEY = 'main_nav';
    /**
     * @var string
     */
    protected $table = 'menu';

    /**
     * Fields from DB.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'link', 'parent_id', 'order', 'is_coming_soon', 'is_new', 'list', 'auth_only', 'is_beta'
    ];

    protected function casts(): array
    {
        return [
            'is_coming_soon' => 'boolean',
            'is_new' => 'boolean',
            'auth_only' => 'boolean',
            'is_beta' => 'boolean',
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Get softDeleted items.
     *
     * @param mixed $value
     * @param null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return in_array(SoftDeletes::class, class_uses($this))
            ? $this->where($this->getRouteKeyName(), $value)->withTrashed()->first()
            : parent::resolveRouteBinding($value);
    }

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
     * Filtering table.
     *
     * @param $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public static function getCached(bool $refresh = false): array
    {
        try {
            if (Cache::has(self::CACHE_KEY) && $refresh) {
                Cache::forget(self::CACHE_KEY);
            }

            if (!Cache::has(self::CACHE_KEY)) {
                Cache::rememberForever(self::CACHE_KEY, function () {
                    return Menu::query()
                        ->orderBy('order')
                        ->where('parent_id', 0)
                        ->get()
                        ->transform(function ($menu) {
                            return [
                                'id' => Str::uuid(),
                                'name' => $menu->name,
                                'link' => $menu->link,
                                'target' => Str::startsWith($menu->link, '/') ? '_self': '_blank',
                                'is_coming_soon' => $menu->is_coming_soon,
                                'is_new' => $menu->is_new,
                                'auth_only' => $menu->auth_only,
                                'is_beta' => $menu->is_beta,
                                'list' => $menu->list,
                                'children' => $menu->children()->get(['name', 'link', 'is_coming_soon', 'is_new'])->toArray()
                            ];
                        })->toArray();
                });
            }

            return Cache::get(self::CACHE_KEY);
        } catch (\Throwable $exception) {
            Log::error('[getCachedMenu] ' . $exception->getMessage());

            return [];
        }
    }
}
