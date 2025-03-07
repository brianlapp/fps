<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\ArticleRequest;
use App\Models\Admin;
use App\Models\Article;
use \App\Http\Resources\Admin\AdminShort;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\Article as ArticleResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use \App\Http\Resources\Admin\Topic as TopicResource;

class ArticlesController
{
    private Admin $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'status', 'pages', 'improved', 'featured']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = Article::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(ArticleResource::collection($list->getCollection())->collection);
        $statuses = Article::STATUSES;

        return Inertia::render('Admin/Articles/Index', compact('list', 'filters', 'statuses'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $authors = $this->admin->can('updateAuthor') ? Admin::all() : [];
        $topics = Topic::query()->active()->get();

        return Inertia::render('Admin/Articles/Edit', [
            'authors' => AdminShort::collection($authors),
            'topics' => TopicResource::collection($topics)
        ]);
    }

    public function store(ArticleRequest $request): RedirectResponse
    {
        $article = new Article();
        $data = $request->validated();
        if ($this->admin->cannot('updateAuthor')) {
            $data['created_by'] = $this->admin->id;
        }
        $data['uuid'] = Str::uuid();
        $article->fill($data);
        $article->published_at = $article->isPublished() ? Carbon::now('UTC') : null;

        $article->save();
        $article->syncTags($data['tags']);
        $article->topics()->sync(array_column($data['topics'], 'id'));
        $article->updateImage($request->get('image'));

        return Redirect::route('admin.articles.edit', $article->id)->with('message', 'Article Created');
    }

    public function edit(Article $article, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $article)) {
            return \redirect()->route('admin.articles.index')->with('error', 'Access Denied');
        }
        $authors = $this->admin->can('updateAuthor') ? Admin::all() : [];
        $topics = Topic::query()->active()->get();

        return Inertia::render('Admin/Articles/Edit', [
            'article' => new ArticleResource($article),
            'authors' => AdminShort::collection($authors),
            'topics' => TopicResource::collection($topics)
        ]);
    }

    /**
     * Update the Article information.
     */
    public function update(Article $article, ArticleRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $article)) {
            return \redirect()->route('admin.articles.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();
        if ($this->admin->cannot('updateAuthor')) {
            unset($data['created_by']);
        }

        $article->fill($data);
        if ($article->isDirty('status')) {
            $article->published_at = $article->isPublished() ? Carbon::now('UTC') : null;
        }
        $article->save();
        $article->syncTags($data['tags']);
        $article->topics()->sync(array_column($data['topics'], 'id'));
        $article->updateImage($request->get('image'));

        return Redirect::back()->with('message', 'Article Updated');
    }

    /**
     * Generate an Image for the Article
     */
    public function generateImage(Article $article): JsonResponse
    {
        try {
            $response = $article->generateImage(false);
            $image = file_get_contents($response->url);

            return \response()->json([
                'image' => 'data:image/jpg;base64,' . base64_encode($image),
                'caption' => $response->caption,
                'error' => null
            ]);
        } catch (\Throwable $exception) {
            Log::error('[ArticlesController@generateImage]: Article ID ' . $article->id . '; Error - ' . $exception->getMessage());
            return \response()->json([
                'image' => null,
                'caption' => null,
                'error' => __('Something Went Wrong with Image Generation! Try Again!')
            ], 500);
        }
    }

    /**
     * Improve the Article's content
     */
    public function improve(Article $article): JsonResponse
    {
        try {
            $content = $article->improve(false);

            return \response()->json([
                'content' => $content,
                'error' => null
            ]);
        } catch (\Throwable $exception) {
            Log::error('[ArticlesController@improve]: Article ID ' . $article->id . '; Error - ' . $exception->getMessage());
            return \response()->json([
                'content' => null,
                'caption' => null,
                'error' => __('Something Went Wrong with Article Improving! Try Again!')
            ], 500);
        }
    }

    /**
     * Resets all Featured articles is_featured state
     */
    public function resetFeatured(): JsonResponse
    {
        try {
            Article::query()->featured()->update(['is_featured' => false]);

            return \response()->json([
                'error' => null
            ]);
        } catch (\Throwable $exception) {
            Log::error('[ArticlesController@resetFeatured]: Error - ' . $exception->getMessage());
            return \response()->json([
                'error' => __('Something Went Wrong with Featured Resetting! Try Again!')
            ], 500);
        }
    }

}
