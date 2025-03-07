<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

class TagsController extends Controller
{
    public function search(Request $request): JsonResponse
    {
        return response()->json([
            'tags' => Tag::query()
                ->containing($request->get('query'), app()->getLocale())
                ->limit($request->get('limit', 20))
                ->get()->transform(fn($tag) => $tag->only(['id', 'name']))
        ]);
    }
}
