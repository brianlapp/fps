<?php

namespace App\Http\Controllers\Traits;

use App\Http\Resources\ArticlePublic;
use App\Models\Admin;
use App\Models\Article;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

trait FeedTrait
{
    private function getArticlesForFeed(?Admin $author = null, ?Topic $topic = null, ?User $promptAuthor = null, ?User $favoritesOf = null): LengthAwarePaginator
    {
        $query = Article::published()->withReferences();
        if (!empty($topic)) {
            $query = $topic->articles()->published()->withReferences();
        }
        if (!empty($author)) {
            $query->where('created_by', $author->id ?? 0);
        }
        if (!empty($favoritesOf)) {
            $query = $favoritesOf->favoriteArticles()->published()->withReferences();
        }
        $tags = array_filter(explode(',', request()->get('tags', '')));
        $my = \request()->get('my') === 'true';
        switch (request()->get('sort')) {
            case 'oldest':
                $query->orderBy('published_at', 'ASC');
                break;
            default:
                $query->orderBy('published_at', 'DESC');
        }
        if (!empty($tags)) {
            $query->withAnyTags($tags);
        }
        if ($my) {
            $query->whereHas('request', function (Builder $subQuery) {
                $user = Auth::getUser();
                $subQuery->where('user_id', $user?->id ?? 0)->orWhere('client_uuid', request()->get('fingerprint'));;
            });
        }
        if (!empty($promptAuthor)) {
            $query->whereHas('request', function (Builder $subQuery) use ($promptAuthor) {
                $subQuery->where('user_id', $promptAuthor->id)->orWhere('client_uuid', $promptAuthor->uuid);;
            });
        }
        $articles = $query->paginate(request()->get('per_page', 18));
        $articles->setCollection(ArticlePublic::collection($articles->getCollection())->collection);

        return $articles;
    }
}
