<?php

namespace App\Console\Commands;

use App\Helpers\HtmlHelper;
use App\Models\Article;
use Illuminate\Console\Command;

class FixBrokenHtml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:fix-html';

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
        $query = Article::query()->published();
        $query->chunk(50, function ($articles) {
            foreach ($articles as $article) {
                try {
                    $this->info('Article #' . $article->id);
                    $content = HtmlHelper::processHtmlString($article->content);

                    $article->content = $content['html'];
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
