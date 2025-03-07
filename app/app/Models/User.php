<?php

namespace App\Models;

use App\Exceptions\WriterException;
use App\Helpers\HtmlHelper;
use App\Jobs\ClearBio;
use App\Jobs\ImproveArticle;
use App\Services\Writer;
use Cviebrock\EloquentSluggable\Sluggable;
use Google\Collection;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, InteractsWithMedia, Sluggable;

    private $favoriteArticlesList = null;

    const DEFAULT_AVATAR = '/build/images/avatar.webp';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'github_id',
        'linkedin_id',
        'avatar',
        'uuid',
        'state',
        'country',
        'gender',
        'prompt_attribution',
        'bio'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'prompt_attribution' => 'boolean'
        ];
    }

    public function hasPassword(): bool
    {
        return !empty($this->attributes['password']);
    }

    public function getNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function userInfo(): HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id', 'id');
    }

    public function saveInfo(array $info): void
    {
        $userInfo = $this->userInfo;
        if (empty($userInfo)) {
            $userInfo = new UserInfo();
            $info['user_id'] = $this->id;
        }
        $userInfo->setRawAttributes($info);

        $userInfo->save();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumbnail')
            ->fit(Fit::Contain, 150, 150)
            ->format('webp')
            ->nonQueued();

        $this->addMediaConversion('webp')
            ->quality(80)->format('webp')
            ->withResponsiveImages()
            ->nonQueued();;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile()->useFallbackUrl($this->avatar ?? self:: DEFAULT_AVATAR);
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
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }

    /**
     * @param bool $save
     * @return string
     * @throws WriterException
     */
    public function clearBio(bool $save = true): string
    {
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        $clearedBio = $writer->clearText($this->bio);


        if ($save) {
            $this->bio = $clearedBio;
            $this->saveQuietly();
        }

        return $clearedBio;
    }

    public function clearBioInBackground(): void
    {
        dispatch(new ClearBio($this));
    }

    public function plannerItems(): BelongsToMany
    {
        return $this->belongsToMany(PlannerItem::class)->withTimestamps();
    }

    public function getPlannerProgress(int $categoryId = null): int
    {
        $itemsQuery = PlannerItem::query();
        $checkedItemsQuery = $this->plannerItems();
        if (!empty($categoryId)) {
            $itemsQuery->whereHas('plannerSection', function ($q) use ($categoryId) {
                $q->where('planner_category_id', $categoryId);
            });
            $checkedItemsQuery->whereHas('plannerSection', function ($q) use ($categoryId) {
                $q->where('planner_category_id', $categoryId);
            });;
        }
        $itemsCount = $itemsQuery->count();
        $checkedItemsCount = $checkedItemsQuery->count();

        if ($itemsCount < 1 || $checkedItemsCount < 1) {
            return 0;
        }

        return round(($checkedItemsCount / $itemsCount) * 100);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function favoriteArticles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'favorite_articles', 'user_id', 'article_id')
            ->withTimestamps();
    }

    public function getFavoriteArticlesList(): array
    {
        if(is_null($this->favoriteArticlesList)) {
            $this->favoriteArticlesList = $this->favoriteArticles()->get(['slug'])->pluck('slug');
        }

        return $this->favoriteArticlesList->toArray();
    }
}
