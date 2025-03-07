<?php

namespace App\Jobs;

use App\Models\ProductReview;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ModerateProductReview implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly ProductReview $review)
    {
        //
    }

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId(): string
    {
        return 'moderate-review-' . $this->review->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->review->moderate();
    }
}
