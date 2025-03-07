<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class CleanBrokenLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:clean-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Article::query()->published()->where('was_improved', true);
        $query->chunk(50, function ($articles) {
            foreach ($articles as $article) {
                try {
                    $this->info('Article #' . $article->id);
                    $dom = new \DOMDocument();
                    // Load the HTML string
                    @$dom->loadHTML($article->content);
                    $count = 0;
                    // Removing Broken Links
                    $links = $dom->getElementsByTagName('a');
                    $this->info('Total Links: ' . $links->length);
                    for ($i = $links->length - 1; $i >= 0; $i--) {
                        $link = $links->item($i);
                        if (strtolower($link->nodeValue) === 'source') {
                            $link->parentNode->removeChild($link);
                        } else {
                            // Create a new <span> element
                            $span = $dom->createElement('span', $link->nodeValue);
                            // Replace the <a> element with the <span> element
                            $link->parentNode->replaceChild($span, $link);
                        }
                    }
                    $article->content = str_replace('()', '', $dom->saveHTML());
                    $article->was_improved = false;
                    $article->save();
                } catch (\Throwable $exception) {
                    $this->error($exception->getMessage());
                }
                $this->info('-------------------------------------------');

            }
        });
//        $bar->finish();
    }
}
