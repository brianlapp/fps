<?php

namespace App\Services;

use App\Exceptions\WriterException;
use App\Models\AiSettings;
use App\Models\Article;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Writer
{
    const LOG_CHANNEL = 'writer';
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.writer.api.url'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'API-TOKEN' => config('services.writer.api.token'),
            ],
            'verify' => false,
            'timeout' => 240,
            'connect_timeout' => 240
        ]);
    }

    /**
     * @param string $method
     * @param string $url
     * @param $payload
     * @return mixed
     * @throws WriterException
     */
    private function makeRequest(string $method, string $url, $payload): mixed
    {
        try {

            $response = $this->client->request($method, $url, [
                'json' => $payload
            ]);

            if ($response->getStatusCode() !== 200) {
                throw new WriterException($response->getReasonPhrase());
            }

            $parsedResponse = json_decode((string)$response->getBody());

            if (!empty($parsedResponse->error)) {
                throw new WriterException($parsedResponse->error, $parsedResponse);
            }

            return $parsedResponse->response;
        } catch (GuzzleException|\Throwable $exception) {
            Log::channel(self::LOG_CHANNEL)->error($exception->getMessage());

            if ($exception instanceof WriterException) {
                throw $exception;
            }
            throw new WriterException($exception->getMessage(), $parsedResponse ?? ['error' => $exception->getMessage()]);
        }
    }

    /**
     * @throws WriterException
     */
    public function articles(string $searchQuery, int $qty = 3, array $previousTitles = [], ?object $overrideSettings = null): object|array
    {
        $settings = $overrideSettings ?? AiSettings::where('function', 'articles')->first();
        $instructions = $settings->instructions;
        $prompt = $settings->prompt;
        $temperature = $settings->temperature;
        $model = $settings->model;
        $response = $this->makeRequest('POST', 'articles', compact('searchQuery', 'qty', 'previousTitles',
            'instructions', 'prompt', 'temperature', 'model'));

        return json_decode($response);
    }

    /**
     * @throws WriterException
     */
    public function image(Article $article, ?object $overrideSettings = null): object
    {
        $settings = $overrideSettings ?? AiSettings::where('function', 'image_for_article')->first();
        $instructions = $settings->instructions;
        $prompt = $settings->prompt;
        $model = $settings->model;
        // We need to limit content length to 3000 chars since DallE 4 accepts max 4000 and we also send prompt
        $content = substr(strip_tags($article->content), 0, 3000);

        return $this->makeRequest('POST', 'image', [
            'article' => $content,
            'instructions' => $instructions,
            'prompt' => $prompt,
            'model' => $model,
        ]);
    }

    /**
     * @throws WriterException
     */
    public function improveArticle(Article $article): string
    {
        return $this->makeRequest('POST', 'articles/improve', [
            'article' => $article->content,
        ]);
    }

    /**
     * @throws WriterException
     */
    public function clearText(string $text): string
    {
        return $this->makeRequest('POST', 'clear-text', compact('text'));
    }

    /**
     * @throws WriterException
     */
    public function censor(string $content): bool
    {
        $response = $this->makeRequest('POST', 'censor', compact('content'));
        Log::channel(self::LOG_CHANNEL)->info($content. ' --->  ' . $response);
        return strtolower($response) === 'yes';
    }


    /**
     * @throws WriterException
     */
    public function activity(string $input): mixed
    {
        return $this->makeRequest('POST', 'activity', compact('input'));
    }

    /**
     * @throws WriterException
     */
    public function censorLite(string $content): bool
    {
        $response = $this->makeRequest('POST', 'censor-lite', compact('content'));
        Log::channel(self::LOG_CHANNEL)->info($content. ' L-->  ' . $response);
        return strtolower($response) === 'yes';
    }
}
