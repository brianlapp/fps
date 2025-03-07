<?php

namespace App\Models;

use App\Exceptions\WriterException;
use App\Jobs\GenerateActivity;
use App\Models\Traits\HasSingleImage;
use App\Services\Writer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use function Laravel\Prompts\select;

class Activity extends Model
{
    use SoftDeletes,
        HasSingleImage;

    const STATUSES = [
        'generating' => 1,
        'success' => 2,
        'error' => 3,
    ];

    const INPUT_PARAMS = [
        'child_age',
        'available_time',
        'location',
        'activity_type_preferences',
        'available_resources',
        'special_requests',
    ];

    protected $fillable = [
        'uuid',
        'user_id',
        'child_age',
        'available_time',
        'location',
        'activity_type_preferences',
        'available_resources',
        'special_requests',
        'status',
        'error',
        'result',
        'generated_at',

        'title',
        'brief_description',
        'age_range',
        'time_required',
        'materials_needed',
        'is_duplicate',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
        'activity_type_preferences' => 'json',
        'materials_needed' => 'json',
        'is_duplicate' => 'boolean',
    ];

    /**
     * Filtering table.
     *
     * @param $query
     * @param array $filters
     */
    public function scopeFilter($query, array $filters): void
    {
        $query->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        })->when($filters['status'] ?? null, function ($query, $status) {
            $query->where('status', $status);
        })->when($filters['child_age'] ?? null, function ($query, $value) {
            $query->where('child_age', $value);
        })->when($filters['available_time'] ?? null, function ($query, $value) {
            $query->where('available_time', $value);
        })->when($filters['location'] ?? null, function ($query, $value) {
            $query->where('location', $value);
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusStringAttribute(): string
    {
        $statuses = array_flip(self::STATUSES);

        return $statuses[$this->status] ?? 'generating';
    }

    public function generate(): void
    {
        $this->status = self::STATUSES['generating'];
        $this->saveQuietly();
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        try {
            $input = [];

            foreach ($this->only(self::INPUT_PARAMS) as $param => $value) {
                if (is_array($value)) {
                    $value = implode(', ', $value);
                }
                $input[] = str_replace('_', ' ', $param) . ': ' . $value;
            }


            $response = $writer->activity(implode(PHP_EOL, $input));
            $this->result = $response->result ?? '';
            $data = json_decode(json_encode($response->data ?? []), true);
            $this->fill($data);
            $this->generated_at = Carbon::now('UTC');
            $this->status = self::STATUSES['success'];
            $this->is_duplicate = Activity::query()->where('title',$data['title'] ?? 'NONE')->exists();

        } catch (WriterException $exception) {
            $this->error = $exception->getMessage();
            $this->status = self::STATUSES['error'];
        }

        $this->saveQuietly();
    }

    public function generateInBackground(): void
    {
        dispatch(new GenerateActivity($this));
    }

    public function scopeSuccessful(Builder $query): void
    {
        $query->whereStatus(self::STATUSES['success']);
    }

    public function scopeOriginal(Builder $query): void
    {
        $query->where('is_duplicate', false);
    }

    public function scopeNotFailed(Builder $query): void
    {
        $query->where('status', '!=', self::STATUSES['error']);
    }
}
