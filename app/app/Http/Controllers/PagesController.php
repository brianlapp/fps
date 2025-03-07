<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticlePublic;
use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PagesController extends Controller
{
    public function __invoke(string $slug)
    {
        /**
         * @var $pageModel Article
         */
        $pageModel = Article::query()->pages()->published()->whereSlug($slug)->first();

        if (empty($pageModel)) {
            abort(404);
        }
        $page = new ArticlePublic($pageModel);

        return Inertia::render('Page', compact('page',))->withViewData(['meta' => [
            'title' => $pageModel->seo_title ?? $pageModel->title,
            'headline' => $pageModel->seo_headline ?? $pageModel->headline,
            'link' => route('articles.show', $pageModel->slug),
            'image' => $pageModel->getFirstMediaUrl('image', 'webp'),
            'googleCard' => [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $pageModel->seo_title ?? $pageModel->title,
                'image' => [
                    '@type' => 'ImageObject',
                    'url' => $pageModel->getFirstMediaUrl('image', 'webp'),
                    'height' => 731,
                    'width' => 1280,
                    'caption' => $pageModel->image_caption ?? ($pageModel->seo_title ?? $pageModel->title)

                ],
                'datePublished' => $pageModel->published_at->toIsoString(),
                'dateModified' => $pageModel->updated_at->toIsoString(),
                'author' => [
                    [
                        '@type' => 'Person',
                        'name' => $pageModel->author->name,
                        'url' => route('author.show', $pageModel->author->slug)
                    ],
                ],
            ]
        ]]);
    }
}
