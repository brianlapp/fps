<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Http\Resources\ArticlePublic;
use App\Http\Resources\ArticleRequestPublic;
use App\Models\AiSettings;
use App\Models\Article;
use App\Models\ArticleRequest;
use Google\Service\CustomSearchAPI;
use Google_Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SearchController
{
    public function index()
    {
//        $ids = ArticleRequest::query()->latest()->limit(9)->verified()
//            ->selectRaw('MAX(id) as id, search_query, MAX(created_at) as created_at')->groupBy('search_query')->pluck('id');
//        $searches = ArticleRequest::whereIn('id', $ids)->latest()->get()
//            ->transform(fn(ArticleRequest $request) => [
//                'id' => $request->uuid,
//                'search_query' => $request->search_query,
//                'url' => \route('search') . '?' . http_build_query([
//                        'request_id' => $request->uuid,
//                        'query' => $request->search_query
//                    ])
//            ]);
        $searches = [];
        $articles = ArticlePublic::collection(Article::featured()->limit(3)->get());

        return Inertia::render('Home/Page', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'latestSearches' => $searches,
            'trendingArticles' => $articles
        ]);
    }

    public function search(Request $request)
    {
        $user = Auth::getUser();
        $query = ArticleRequest::query()->whereUuid($request->get('request_id'));
        $articleRequest = $query->first();
        if (empty($articleRequest)) {
            return redirect(route('home'));
        }
        $localArticles = Article::search($articleRequest->search_query)->take(5)->get();
        $settings = AiSettings::where('function', 'live_article')->first();

        $payload = [
            'searchQuery' => $articleRequest->search_query,
            'previousTitles' => Article::search($articleRequest->search_query)
                ->take(100)->get()->pluck('title')->toArray(),
            'token' => config('services.writer.api.token'),
            'instructions' => $settings->instructions,
            'prompt' => $settings->prompt,
            'model' => $settings->model,
            'temperature' => $settings->temperature,
        ];
        $json = json_encode($payload);
        $key = config('services.writer.cryptKey');
        $encrypted = $key . strrev($json) . strrev($key);
        $liveArticle = $articleRequest->articles()->live()->first();

        return Inertia::render('Results/Page', [
            'results' => $this->googleSearch($articleRequest->search_query),
            'localArticles' => ArticlePublic::collection($localArticles),
            'articleRequest' => new ArticleRequestPublic($articleRequest),
            'livePayload' => [
                'pid' => base64_encode($encrypted)
            ],
            'liveUrl' => config('services.writer.api.url') . 't',
            'liveArticle' => !empty($liveArticle) ? new ArticlePublic($liveArticle) : null
        ]);
    }

    public function submit(SearchRequest $request)
    {
        $user = Auth::getUser();
        /**
         * @var  $articleRequest ArticleRequest
         */
        $articleRequest = ArticleRequest::create([
            'uuid' => Str::uuid(),
            'client_uuid' => $request->get('fingerprint'),
            'user_id' => $user->id ?? null,
            'qty' => 2,
            'search_query' => $request->get('query')
        ]);
        $request->merge(['request_id' => $articleRequest->uuid->toString()]);

        return Inertia::location(route('search') . '?' . http_build_query($request->only(['query', 'request_id'])));
    }

    public function requestStatus(string $uuid)
    {
        try {
            $articleRequest = ArticleRequest::query()->whereUuid($uuid)->first();
            if (empty($articleRequest)) {
                return response()->json([
                    'error' => 'Not Found',
                    'request' => null
                ], 404);
            }

            return response()->json([
                'error' => null,
                'request' => new ArticleRequestPublic($articleRequest)
            ]);

        } catch (\Throwable $exception) {
            Log::error('[SearchController.requestStatus]: ' . $exception->getMessage());

            return response()->json([
                'error' => 'Error',
                'request' => null
            ], 500);
        }
    }

    private function googleSearch(string $q): array
    {
        try {
            $client = new Google_Client();
            $client->setApplicationName('New Search');
            $client->setDeveloperKey(config('services.google.search.key'));

            $service = new CustomSearchAPI($client);

            $result = $service->cse->listCse(['q' => $q, 'num' => 5, 'cx' => config('services.google.search.id'), 'safe' => 'active']);

            return $result->getItems();
        } catch (\Throwable $e) {
            Log::error('Google Search: ' . $e->getMessage());

            return [];
        }

    }
}
