<?php

namespace App\Models;

use App\Events\ProductReviewApprovedEvent;
use App\Jobs\ModerateProductReview;
use App\Services\Writer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductReview extends Model
{
    use SoftDeletes;

    const STATUSES = [
        'pending' => 0,
        'approved' => 1,
        'declined' => 2,
        'flagged' => 3
    ];

    const MAX_RATE = 5;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'product_id',
        'user_id',
        'title',
        'content',
        'rate',
        'status',
        'is_verified_by_ai',
        'ai_error',
        'is_flagged',
        'flagged_by',
        'vote_rating',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_verified_by_ai' => 'boolean',
        'is_flagged' => 'boolean',
    ];

    /**
     * Get the product associated with the review.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who created the review.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who flagged the review.
     */
    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
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
                $query->where('title', 'like', '%' . $search . '%')->orWhere('content', 'like', '%' . $search . '%')->orWhereHas('user', function ($subQuery) use ($search) {
                    $subQuery->where('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['product'] ?? null, function ($query, $productId) {
            $query->where('product_id', $productId);
        })->when($filters['rate'] ?? null, function ($query, $rate) {
            $query->where('rate', $rate);
        })->when(array_key_exists('status', $filters) , function ($query, $status)  use ($filters) {
            $query->where('status', $filters['status']);
        });
    }

    public function scopeApproved(Builder $query): void
    {
        $query->where('status', self::STATUSES['approved']);
    }

    public function scopePublic(Builder $query, ?User $user = null): void
    {
        $query->where(function (Builder $subquery) use ($user) {
            $subquery->approved();
            if (!empty($user)) {
                $subquery->orWhere(function (Builder $subSubquery) use ($user) {
                    $subSubquery->where('user_id', $user->id)->where('status', self::STATUSES['pending']);
                });
            }
        });
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUSES['pending'];
    }

    public function approve(): void
    {
        $this->status = self::STATUSES['approved'];
        $this->save();
        event(new ProductReviewApprovedEvent($this));
    }

    public function decline(): void
    {
        $this->status = self::STATUSES['declined'];
        $this->save();
    }

    public function moderate(): void
    {
        try {
            /**
             * @var $writer Writer
             */
            $writer = resolve(Writer::class);
            $content = $this->title . ' /n/n ' . $this->content;
            $isValid = $writer->censorLite($content);

            $this->is_verified_by_ai = true;
            if ($isValid) {
                $this->approve();
            } else {
                $this->decline();
            }
        } catch (\Throwable $exception) {
            $this->ai_error = $exception->getMessage();
            $this->save();
        }
    }

    public function moderateInBackground(): void
    {
        dispatch(new ModerateProductReview($this));
    }

    public function getStatusStringAttribute(): string
    {
        $statuses = array_flip(self::STATUSES);

        return $statuses[$this->status] ?? 'pending';
    }

    /**
     * Get softDeleted Product Reviews.
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
}
