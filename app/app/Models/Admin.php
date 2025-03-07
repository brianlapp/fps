<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Admin extends Authenticatable implements HasMedia
{
    use Notifiable, SoftDeletes, InteractsWithMedia, Sluggable;

    const DEFAULT_AVATAR = '/build/images/avatar.webp';

    protected $guarded = ['id'];

    public string $guard = 'admin';

    const ROLES = [
        1 => 'admin',
        2 => 'moderator',
        3 => 'author'
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
                'source' => 'name',
                'onUpdate' => true
            ]
        ];
    }

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
                $query->where('name', 'like', '%' . $search . '%')->orWhere('email', 'like', '%' . $search . '%');
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['role'] ?? null, function ($query, $roles) {
            $query->whereIn('role', $roles);
        });
    }

    /**
     * Get softDeleted admins.
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
        $this->addMediaCollection('avatar')->singleFile()->useFallbackUrl(self:: DEFAULT_AVATAR);
    }

    public static function getAiWriter(): Admin
    {
        $payload = [
            'name' => 'Emma Davis',
            'email' => 'emma.davis@freeparentsearch.com',
            'role' => 3,
            'title' => 'AI Writer',
            'password' => Hash::make(Str::random())
        ];

        /**
         * @var $record Admin
         */
        $record = self::firstOrCreate(['email' => $payload['email']], $payload);

        if ($record->wasRecentlyCreated) {
            $record->addMedia(resource_path('images/robot.webp'))
                ->usingFileName(Str::random(24))
                ->toMediaCollection('avatar');
        }

        return $record;
    }

    public function getRoleStringAttribute($key): string
    {
        return self::ROLES[$this->role] ?? 'author';
    }

    public function isAdmin(): bool
    {
        return $this->role_string === 'admin';
    }
}
