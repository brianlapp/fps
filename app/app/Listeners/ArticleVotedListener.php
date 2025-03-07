<?php

namespace App\Listeners;

use App\Events\ArticleVoted;
use App\Models\ArticleVote;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ArticleVotedListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(ArticleVoted $event): void
    {
        $article = $event->getArticle();
        $query =  ArticleVote::query()->where('article_id', $article->id);
        $article->vote_cnt = $query->clone()->count();
        $article->vote_sum = $query->clone()->sum('vote') ?? 0;
        $article->vote_avg = $query->clone()->avg('vote') ?? 0;
        $stats = [];
        for($i = ArticleVote::MIN_VOTE - 1; ++$i, $i <= ArticleVote::MAX_VOTE;) {
            $stats[$i] = $query->clone()->where('vote', $i)->count();
        }
        $article->vote_stat = $stats;
        $article->saveQuietly();
    }
}
