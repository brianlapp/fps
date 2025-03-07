<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class DataReportingService
{
    const LOG_CHANNEL = 'data-reporting';

    /**
     * @var $client client
     */
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.data_reporting.api_url'),
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => config('services.data_reporting.api_key'),
            ],
            'verify' => false
        ]);
    }

    /**
     * @param $type
     * @param $url
     * @param array $body
     * @return mixed|null
     */
    private function sendRequest($type, $url, $body = []): mixed
    {
        try {

            $response = $this->client->request($type, $url, [
                'json' => $body,
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            if ($response['statusCode'] !== 200) {
                throw new \Exception($response['statusMessage'], $response['statusCode']);
            }

            return $response['response'] ?? null;

        } catch (GuzzleException $exception) {
            $error = [
                'url' => $url,
                'body' => $body,
                'error' => null
            ];
            if (!method_exists($exception, 'getResponse') || empty($exception->getResponse())) {
                $error['error'] = $exception->getMessage() ?? 'Unknown Error';
            } else {
                $error['error'] = $exception->getResponse()->getReasonPhrase() ?? 'Unknown Error';
            }

            Log::channel(self::LOG_CHANNEL)->error($error);

            return null;
        } catch (\Throwable $exception) {
            $error = [
                'url' => $url,
                'body' => $body,
                'error' => $exception->getMessage()
            ];
            Log::channel(self::LOG_CHANNEL)->error($error);

            return null;
        }
    }

    /**
     * @param $postcode
     * @return mixed|null
     */
    public function zipLookup($postcode): mixed
    {
        return $this->sendRequest('POST', 'track/zip-lookup', ['postcode' => $postcode]);

    }

    /**
     * @param string $email
     * @param string $provider
     * @return ?bool
     */
    public function checkEmail(string $email, string $provider): ?bool
    {
        $response = $this->sendRequest('POST', 'track/ctst-email?provider=' . $provider, compact(['email', 'provider']));
        return !$response;
    }

    /**
     * @param array $payload
     * @return ?bool
     */
    public function addLead(array $payload): ?bool
    {
        $payload['project'] = 'fps';
        return $this->sendRequest('POST', 'track/lead', $payload);
    }
}
