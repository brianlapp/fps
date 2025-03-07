<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FeedTrait;
use App\Http\Resources\Author;
use App\Http\Resources\TopicPublic;
use App\Models\Admin;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TopicController extends Controller
{
    use FeedTrait;

    public function index(string $slug, Request $request)
    {
        $topic = Topic::where('slug', $slug)->first();
        if (empty($topic)) {
            abort(404);
        }
        return Inertia::render('Topic', [
            'topic' => new TopicPublic($topic),
            'articles' => $this->getArticlesForFeed(null, $topic)
        ])->withViewData(['meta' => [
            'title' => $topic->title,
            'headline' => $topic->headline,
            'image' => $topic->getFirstMediaUrl('avatar', 'webp'),
            'link' => route('topics.show', $topic->slug),
        ]]);
    }

    public function loadFeed(string $slug): JsonResponse
    {
        $topic = Topic::where('slug', $slug)->first();
        $articles = $this->getArticlesForFeed(null, $topic);

        return response()->json($articles);
    }
}
