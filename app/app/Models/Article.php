<?php

namespace App\Models;

use App\Exceptions\WriterException;
use App\Helpers\HtmlHelper;
use App\Jobs\GenerateArticleImage;
use App\Jobs\ImproveArticle;
use App\Models\Traits\HasSingleImage;
use App\Services\Writer;
use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Scout\Searchable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Spatie\MediaLibrary\HasMedia;
use Spatie\Tags\HasTags;
use Spatie\Tags\Tag;

class Article extends Model implements HasMedia, Feedable
{
    use Sluggable,
        HasTags,
        Searchable,
        SoftDeletes,
        HasSingleImage;

    protected static function booted(): void
    {
        static::addGlobalScope(function (Builder $query) {
            $query->where('is_page', false);
        });

        static::addGlobalScope('references', function (Builder $query) {
            $query->whereNotIn('type', [self::TYPES['reference']]);
        });
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs(): string
    {
        return config('scout.prefix') . 'Articles';
    }

    /**
     * Make the given model instance searchable.
     *
     * @return void
     */
    public function searchable(): void
    {
        // Make searchable this model
        $this->newCollection([$this])->searchable();
    }

    /**
     * Remove the given model instance from the search index.
     *
     * @return void
     */
    public function unsearchable(): void
    {
        $this->newCollection([$this])->unsearchable();
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->isPublished();
    }

    public function toSearchableArray(): array
    {

        return [
            'id' => (int)$this->id,
            'uuid' => $this->uuid,
            'title' => $this->title,
            'headline' => $this->headline,
            'link' => route('articles.show', $this->slug)
        ];
    }

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

    const STATUSES = [
        'draft' => 0,
        'published' => 1,
        'scheduled' => 2
    ];

    const SOURCES = [
        'ai' => 1,
        'author' => 2
    ];

    const TYPES = [
        'plain' => 1,
        'live' => 2,
        'reference' => 3
    ];
    protected $guarded = ['id'];

    protected $casts = [
        'published_at' => 'datetime',
        'is_page' => 'boolean',
        'is_image_being_generated' => 'boolean',
        'was_improved' => 'boolean',
        'is_featured' => 'boolean',
        'vote_stat' => 'json'
    ];

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = self::STATUSES[$value] ?? self::STATUSES['draft'];
    }

    public function getStatusAttribute(): string
    {
        $revertedStatuses = array_flip(self::STATUSES);
        return $revertedStatuses[$this->attributes['status'] ?? 0] ?? 'draft';
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function isScheduled(): bool
    {
        return $this->status === 'scheduled';
    }

    public function publish(): void
    {
        $this->status = 'published';
        $this->published_at = Carbon::now('UTC');
        $this->save();
    }

    public function scopePublished(Builder $query): void
    {
        $query->where('status', self::STATUSES['published']);
    }

    public static function findPublished(string $slug): ?Article
    {
        return self::query()->published()->whereSlug($slug)->first();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'created_by');
    }

    public function getHeadlineAttribute(): string
    {
        try {
            preg_match('/<p>(.*?)<\/p>/s', $this->content, $match);
            return html_entity_decode(strip_tags($match[1]));
        } catch (\Throwable $exception) {
            return '';
        }
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
                $query->where('title', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['status'] ?? null, function ($query, $statuses) {
            $query->whereIn('status', $statuses);
        })->when($filters['pages'] ?? null, function ($query) {
            $query->pages();
        })->when($filters['improved'] ?? null, function ($query, $value) {
            $query->where('was_improved', $value === 'yes');
        })->when($filters['featured'] ?? null, function ($query) {
            $query->featured();
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
        return $this->where($this->getRouteKeyName(), $value)->withTrashed()->withoutGlobalScopes()->first();
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopePlain(Builder $query): void
    {
        $query->whereType(self::TYPES['plain']);
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeLive(Builder $query): void
    {
        $query->whereType(self::TYPES['live']);
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopePages(Builder $query): void
    {
        $query->withoutGlobalScopes()->where('is_page', true);
    }

    public function generateImage(bool $save = false): object
    {
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        $image = $writer->image($this);


        if ($save) {
            $this->addMediaFromUrl($image->url)->toMediaCollection('image');
            $this->image_caption = substr($image->caption, 0, 255);
            $this->saveQuietly();
        }

        return $image;
    }

    public function generateImageInBackground(): void
    {
        dispatch(new GenerateArticleImage($this));
    }

    public function request(): BelongsTo
    {
        return $this->belongsTo(ArticleRequest::class, 'request_id');
    }

    public static function getPopularTags(int $limit = 20)
    {
        $class = str_replace('\\', '\\\\', self::class);
        $ids = collect(DB::select("select `tag_id`, count(*) as count FROM `taggables`
            WHERE `taggable_type` = '{$class}'
            GROUP BY (tag_id) ORDER BY count DESC LIMIT 20"))->pluck(['tag_id']);
        return Tag::query()->whereIn('id', $ids)->get()->transform(fn($tag) => $tag->only(['id', 'name']));
    }

    public function topics(): BelongsToMany
    {
        return $this->belongsToMany(Topic::class);
    }

    /**
     * @param bool $save
     * @return string
     * @throws WriterException
     */
    public function improve(bool $save = false): string
    {
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        $rawContent = $writer->improveArticle($this);
        $content = HtmlHelper::processHtmlString($rawContent)['html'];



        if ($save) {
            $this->content = $content;
            $this->was_improved = true;
            $this->saveQuietly();
        }

        return $content;
    }

    public function improveInBackground(): void
    {
        dispatch(new ImproveArticle($this));
    }

    public static function getFeedItems()
    {
        return Article::query()
            ->published()
            ->orderBy('published_at', 'DESC')
            ->get();
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create([
            'id' => route('articles.show', $this->slug),
            'title' => $this->rss_title ?? $this->title,
            'image' => $this->getFirstMediaUrl('image'),
            'link' => route('articles.show', $this->slug),
            'authorName' => !empty($this->author) ? $this->author->name : 'John Doe',
            'authorEmail' => !empty($this->author) ? $this->author->email : 'john.doe@mail.com',
            'updated' => $this->published_at ?? $this->updated_at,
            'summary' => $this->headline
        ]);
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeWithReferences(Builder $query): void
    {
        $query->withoutGlobalScopes(['references']);
    }

    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeFeatured(Builder $query): void
    {
        $query->where('is_featured', true);
    }

    public function favoriteByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_articles', 'article_id', 'user_id')
            ->withTimestamps();
    }

    public function isFavoriteBy(User $user):bool
    {
        return $this->favoriteByUsers()->where('user_id', $user->id)->exists();
    }
}
