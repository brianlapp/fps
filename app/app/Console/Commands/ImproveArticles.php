<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class ImproveArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'articles:improve';

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
        $query = Article::query()->published()->where('was_improved', false);
        $bar = $this->output->createProgressBar($query->clone()->count());
        $bar->start();
        $query->chunk(50, function ($articles) use ($bar) {
            foreach ($articles as $article) {
                try {
                    $article->improve(true);
                    $bar->advance();
                } catch (\Throwable $exception) {
                    $this->error($exception->getMessage());
                }

            }
        });
        $bar->finish();
    }
}
