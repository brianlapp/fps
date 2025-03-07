<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FeedTrait;
use App\Http\Resources\Author;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthorController extends Controller
{
    use FeedTrait;

    public function index(string $slug, Request $request)
    {
        $author = Admin::where('slug', $slug)->first();
        if (empty($author)) {
            abort(404);
        }
        return Inertia::render('Author/Page', [
            'author' => new Author($author),
            'articles' => $this->getArticlesForFeed($author)
        ])->withViewData(['meta' => [
            'title' => $author->name,
            'headline' => $author->short_bio,
            'image' => $author->getFirstMediaUrl('avatar', 'webp'),
            'link' => route('author.show', $author->slug),
        ]]);
    }

    public function loadFeed(string $slug): JsonResponse
    {
        $author = Admin::where('slug', $slug)->first();
        $articles = $this->getArticlesForFeed($author);

        return response()->json($articles);
    }
}
