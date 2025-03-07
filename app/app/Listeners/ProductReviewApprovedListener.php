<?php

namespace App\Listeners;

use App\Events\ProductReviewApprovedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProductReviewApprovedListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ProductReviewApprovedEvent $event): void
    {
        $review = $event->getProductReview();

        $review->product->calculateRating();
    }
}
