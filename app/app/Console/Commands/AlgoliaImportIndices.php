<?php

namespace App\Console\Commands;

use Algolia\AlgoliaSearch\SearchClient;
use App\Models\Article;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AlgoliaImportIndices extends Command
{
    /**
     * Artisan Command name.
     *
     * @var string
     */
    protected $signature = 'algolia:import-settings {index?}';

    /**
     * Artisan command description.
     *
     * @var string
     */
    protected $description = 'Sync Algolia indices settings';

    /**
     * Artisan command functionality.
     *
     * @return int
     */
    public function handle()
    {
        try {
            /**
             * @var SearchClient $client
             */
            $client = SearchClient::create(config('scout.algolia.id'), config('scout.algolia.secret'));

            foreach ($this->getIndices() as $configFile => $indexClass) {
                $indexName = class_exists($indexClass) ? (new $indexClass)->searchableAs() : $indexClass;
                $index = $client->initIndex($indexName);
                $indexConfig = config($configFile, []);

                if (!isset($indexConfig['settings'])) {
                    $this->error($indexClass . ' does not have configuration settings');

                    continue;
                }

                $index->setSettings([
                    ...$indexConfig['settings'],
                    'replicas' => isset($indexConfig['replicas']) ? array_keys($indexConfig['replicas']) : []
                ]);

                // Set replicas config
                if (isset($indexConfig['replicas']) && count($indexConfig['replicas'])) {
                    foreach ($indexConfig['replicas'] as $replicaKey => $replicaConfig) {
                        $replica = $client->initIndex($replicaKey);
                        $replica->setSettings([
                            ...$indexConfig['settings'],
                            ...$replicaConfig
                        ]);
                    }
                }
            }

            $this->info('Settings were successfully imported');

            return Command::SUCCESS;
        } catch (\Exception $exception) {
            Log::error('Algolia Import Settings: ', [
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
            ]);

            $this->error($exception->getMessage());
        }
    }

    /**
     * [algolia_index] => [local_config_file]
     *
     * @return array
     */
    private function getIndices(): array
    {
        $indices = [
            'scout-articles' => Article::class,
        ];

        return $this->argument('index') ? [$this->argument('index') => $indices[$this->argument('index')]] : $indices;
    }
}
