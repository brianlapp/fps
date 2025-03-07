<?php

namespace App\Console\Commands;

use App\Models\Article;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemap extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate the sitemap.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $map = SitemapGenerator::create(config('app.url'))->getSitemap();
        $map->add(Url::create(route('signup'))->setPriority(6)->setLastModificationDate(Carbon::yesterday()));
        $map->add(Url::create(route('contact-us.show-form'))->setPriority(0.6)->setLastModificationDate(Carbon::yesterday()));
        $map->add(Url::create(route('articles.feed'))->setPriority(0.9)->setLastModificationDate(Carbon::yesterday()));
        $map->add(Url::create(route('planner_categories.index'))->setPriority(0.9)->setLastModificationDate(Carbon::yesterday()));

        Article::query()->withoutGlobalScopes()->published()->chunk(100, function ($articles) use ($map) {
            foreach ($articles as $article) {
                if ($article->is_page) {
                    $url = Url::create(route('page', $article->slug));
                } else {
                    $url = Url::create(route('articles.show', $article->slug));
                }
                $image = $article->getFirstMediaUrl('image', 'webp');
                if (!empty($image)) {
                    $url->addImage($article->getFirstMediaUrl('image', 'webp'), $article->title);
                }
                $map->add($url
                    ->setLastModificationDate($article->published_at ?? $article->updated_at)
                    ->setPriority(1)
                );
            }
        });


        $map->writeToFile(public_path('sitemap.xml'));
    }
}
