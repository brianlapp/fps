<?php

namespace App\Models;

use App\Exceptions\WriterException;
use App\Helpers\HtmlHelper;
use App\Jobs\GenerateArticle;
use App\Jobs\VerifyRequest;
use App\Services\Writer;
use Faker\Factory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ArticleRequest extends Model
{
    protected $casts = [
        'is_verified' => 'boolean'
    ];

    const STATUSES = [
        'in_progress' => 0,
        'succeeded' => 1,
        'failed' => 2
    ];
    protected $guarded = ['id'];

    public function setStatusAttribute($value): void
    {
        $this->attributes['status'] = self::STATUSES[$value] ?? self::STATUSES['in_progress'];
    }

    public function getStatusAttribute(): string
    {
        $revertedStatuses = array_flip(self::STATUSES);
        return $revertedStatuses[$this->attributes['status'] ?? 0] ?? 'in_progress';
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'request_id');
    }

    public function isFinished(): bool
    {
        return $this->status !== 'in_progress';
    }

    public function isRunning(): bool
    {
        return $this->status === 'in_progress';
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'succeeded';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function failed(string $message): void
    {
        $this->status = 'failed';
        $this->error = $message;
        $this->save();
    }

    public function succeeded(): void
    {
        $this->status = 'succeeded';
        $this->save();
    }


    public function runGeneration(): void
    {
        GenerateArticle::dispatch($this);
    }

    /**
     * @throws WriterException
     */
    public function generate(): void
    {
        $author = Admin::getAiWriter();
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        $titles = Article::search($this->search_query)
            ->take(100)->get()->pluck('title')->toArray();
        $response = $writer->articles($this->search_query, $this->qty, $titles);

        foreach ($response as $articlePayload) {
            $tags = $articlePayload->tags ?? [];
            if (!$writer->censor(strip_tags($articlePayload->content))) {
                continue;
            }
            $article = $this->articles()->create([
                'uuid' => Str::uuid(),
                'title' => $articlePayload->title,
                'content' => $articlePayload->content,
                'created_by' => $author->id,
                'source' => Article::SOURCES['ai'],
                'is_image_being_generated' => true
            ]);
            $article->publish();
            $article->attachTags($tags);
            $article->improveInBackground();
            $article->generateImageInBackground();
        }

        $this->succeeded();
    }

    public function saveArticleFromHtml(string $html): ?Article
    {
        $author = Admin::getAiWriter();
        $parsedHtml = HtmlHelper::processHtmlString($html);
        $tags = $parsedHtml['tags'] ?? [];
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        if (!$writer->censor(strip_tags($parsedHtml['html']))) {
            return null;
        }
        $article = $this->articles()->create([
            'uuid' => Str::uuid(),
            'title' => $parsedHtml['title'] ?? $this->search_query,
            'content' => $parsedHtml['html'],
            'created_by' => $author->id,
            'source' => Article::SOURCES['ai'],
            'type' => Article::TYPES['live'],
            'is_image_being_generated' => true
        ]);
        $article->publish();
        $article->attachTags($tags);
        $article->generateImageInBackground();
        $article->improveInBackground();

        return $article;
    }

    public function scopeUnique(Builder $query): void
    {
        $query->selectRaw('*, DISTINCT(search_query) as search_query');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeVerified(Builder $query): void
    {
        $query->where('is_verified', true);
    }

    public function verify(): void
    {
        /**
         * @var $writer Writer
         */
        $writer = resolve(Writer::class);
        $isValid = $writer->censor($this->search_query);

        if ($isValid) {
            $this->is_verified = true;
            $this->saveQuietly();
        } else {
            $this->forceDelete();
        }
    }

    public function verifyInBackground(): void
    {
        dispatch(new VerifyRequest($this))->delay(2);
    }
}
