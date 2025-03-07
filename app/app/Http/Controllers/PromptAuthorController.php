<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\FeedTrait;
use App\Http\Resources\Author;
use App\Http\Resources\PromptAuthor;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromptAuthorController extends Controller
{
    use FeedTrait;

    public function index(string $slug, Request $request)
    {
        $author = User::where('slug', $slug)->first();
        if (empty($author)) {
            abort(404);
        }
        return Inertia::render('PromptAuthor/Page', [
            'author' => new PromptAuthor($author),
            'articles' => $this->getArticlesForFeed(null, null, $author)
        ])->withViewData(['meta' => [
            'title' => $author->name,
            'headline' => $author->bio,
            'image' => $author->getFirstMediaUrl('avatar', 'webp'),
            'link' => route('prompt_author.show', $author->slug),
        ]]);
    }

    public function loadFeed(string $slug): JsonResponse
    {
        $author = Admin::where('slug', $slug)->first();
        $articles = $this->getArticlesForFeed($author);

        return response()->json($articles);
    }
}
