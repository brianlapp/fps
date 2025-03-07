<?php

namespace App\Jobs;

use App\Models\ArticleRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class VerifyRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly ArticleRequest $request)
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
        return 'verify-' . $this->request->id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->request->verify();
    }
}
