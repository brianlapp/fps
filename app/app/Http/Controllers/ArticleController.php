<?php

namespace App\Http\Controllers;

use App\Events\ArticleVoted;
use App\Http\Controllers\Traits\FeedTrait;
use App\Http\Resources\OfferCategoryPublic;
use App\Http\Resources\ArticlePublic;
use App\Http\Resources\OfferPublic;
use App\Models\Article;
use App\Models\ArticleRequest;
use App\Models\ArticleVote;
use App\Models\OfferCategory;
use App\Rules\ReCaptcha;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Spatie\Tags\Tag;

class ArticleController
{
    use FeedTrait;

    public function show(string $slug, Request $request)
    {
        $user = Auth::user();
        $articleModel = Article::findPublished($slug);

        if (empty($articleModel)) {
            abort(404);
        }

        $article = new ArticlePublic($articleModel);
        $offerCategories = OfferCategoryPublic::collection(OfferCategory::query()->active()->get());
        $suggestedArticlesQuery = Article::query()->published()->where('id', '!=', $articleModel->id);

        $suggestedArticlesQuery->where(function (Builder $subQuery) use ($articleModel) {
            // Using tags
            $subQuery->whereIn('id', Article::withAnyTags($articleModel->tags->pluck('name')->toArray())->get()->pluck('id')->toArray());

            // Trying to find similar articles using search engine and request prompt if the article was generated by request
            if (!empty($articleModel->request)) {
                $subQuery->orWhereIn('id', Article::search($articleModel->request->search_query)->get()->pluck('id')->toArray());
            }
        });

        $suggestedArticles = ArticlePublic::collection($suggestedArticlesQuery->latest()->limit(6)->get());

        return Inertia::render('Article', compact('article', 'offerCategories', 'suggestedArticles'))->withViewData(['meta' => [
            'title' => $articleModel->seo_title ?? $articleModel->title,
            'headline' => $articleModel->seo_headline ?? $articleModel->headline,
            'link' => route('articles.show', $articleModel->slug),
            'image' => $articleModel->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $articleModel->seo_title ?? $articleModel->title,
                'image' => [
                    '@type' => 'ImageObject',
                    'url' => $articleModel->getFirstMediaUrl('image', 'webp'),
                    'height' => 731,
                    'width' => 1280,
                    'caption' => $articleModel->image_caption ?? ($articleModel->seo_title ?? $articleModel->title)

                ],
                'datePublished' => $articleModel->published_at->toIsoString(),
                'dateModified' => $articleModel->updated_at->toIsoString(),
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => $articleModel->author->name,
                        'url' => route('author.show', $articleModel->author->slug)
                    ],
                ],
            ]
        ]]);
    }

    public function save(Request $request)
    {
        $user = Auth::user();
        try {
            $query = ArticleRequest::query()->whereUuid($request->get('request_id'));
            if (empty($user)) {
                $query->whereClientUuid($request->get('fingerprint'));
            } else {
                $query->whereUserId($user->id);
            }
            $articleRequest = $query->first();
            if (empty($articleRequest)) {
                return response()->json([
                    'article' => null
                ]);
            }
            $article = $articleRequest->saveArticleFromHtml($request->get('html'));
            $articleRequest->verifyInBackground();

            return response()->json([
                'article' => !empty($article) ? new ArticlePublic($article) : null
            ]);

        } catch (\Throwable $throwable) {
            Log::error('[Article Save] ' . $throwable->getMessage() . '
            (User: ' . (!empty($user) ? $user->id : $request->get('fingerprint')) . ', Request: ' . $request->get('request_id'));

            return response()->json([
                'article' => null
            ]);
        }
    }

    public function feed(Request $request)
    {
        $currentSort = $request->get('sort');
        $currentView = $request->get('view');
        $currentMy = $request->get('my') === 'true';
        $currentTags = array_filter(explode(',', $request->get('tags', '')));
        $articles = $this->getArticlesForFeed();

        $popularTags = Article::getPopularTags()->toArray();

        return Inertia::render('Feed/Page', compact('articles', 'currentView', 'currentSort', 'currentTags', 'currentMy', 'popularTags'))->withViewData(['meta' => [
            'title' => 'Feed',
            'link' => route('articles.feed'),
        ]]);
    }

    public function loadFeed(): JsonResponse
    {
        $articles = $this->getArticlesForFeed();

        return response()->json($articles);
    }

    public function addToFavorites($slug, Request $request)
    {
        $user = Auth::user();
        try {
            $article = Article::query()->where('slug', $slug)->first();
            if (!empty($article)) {
                $user->favoriteArticles()->toggle([$article->id]);
            }

            return response()->json([]);

        } catch (\Throwable $throwable) {
            Log::error('[Article addToFavorite] ' . $throwable->getMessage() . '
            (User: ' . (!empty($user) ? $user->id : $request->get('fingerprint')) . ', Slug: ' . $slug);

            return response()->json([]);
        }
    }

    public function favoritesFeed(Request $request)
    {
        $user = Auth::user();
        $currentSort = $request->get('sort');
        $currentView = $request->get('view');
        $currentTags = array_filter(explode(',', $request->get('tags', '')));
        $articles = $this->getArticlesForFeed(favoritesOf: $user);

        $popularTags = Article::getPopularTags()->toArray();

        return Inertia::render('Favorites/Page', compact('articles', 'currentView', 'currentSort', 'currentTags', 'popularTags'))->withViewData(['meta' => [
            'title' => 'My Saved Articles',
            'link' => route('articles.favorites'),
        ]]);
    }

    public function loadFavoritesFeed(): JsonResponse
    {
        $articles = $this->getArticlesForFeed();

        return response()->json($articles);
    }

    public function vote($slug, Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'vote' => [
                'required',
                'int',
                'min:' . ArticleVote::MIN_VOTE,
                'max:' . ArticleVote::MAX_VOTE
            ],
            'recaptcha_response' => ['required', new ReCaptcha()],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->getMessageBag()->first()
            ], 422);
        }
        $user = Auth::user();
        try {
            $article = Article::query()->where('slug', $slug)->first();
            if (!empty($article)) {
                $query = ArticleVote::query()->where('article_id', $article->id);
                if (!empty($user)) {
                    $query->where(function ($q) use ($user, $request) {
                        $q->where('user_id', $user->id);
                        $q->orWhereIn('client_id', [$user->uuid, $request->get('fingerprint')]);
                    });
                } else {
                    $query->where('client_id', $request->get('fingerprint'));
                }
                $record = $query->first();
                if (empty($record)) {
                    $record = new ArticleVote();
                    $record->article_id = $article->id;
                    $record->client_id = $request->get('fingerprint');
                    if (!empty($user)) {
                        $record->user_id = $user->id;
                    }
                }
                $record->vote = $request->get('vote');
                $record->save();

                event(new ArticleVoted($article));
            }

            return response()->json([]);

        } catch (\Throwable $throwable) {
            Log::error('[Article vote] ' . $throwable->getMessage() . '
            (User: ' . (!empty($user) ? $user->id : $request->get('fingerprint')) . ', Slug: ' . $slug);

            return response()->json([]);
        }
    }
}
