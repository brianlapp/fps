<?php

namespace App\Services;


use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class ArcamaxService
{
    /**
     * @var $client Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://www.arcamax.com',
            'verify' => false
        ]);
    }

    /**
     * @param $type
     * @param $url
     * @param array $body
     * @return void
     */
    private function sendRequest($type, $url, $body = [], $query = []): void
    {
        try {

            $response = $this->client->request($type, $url, [
                'query' => $query,
                'json' => $body
            ]);

        } catch (GuzzleException $exception) {
            if (!$exception->getResponse()) {
                Log::error('[ArcamaxService]: ' . $exception->getMessage() ?? 'Unknown Error');
            }
            Log::error('[ArcamaxService]: ' . $exception->getResponse()->getReasonPhrase() ?? 'Unknown Error');
        } catch (\Throwable $exception) {
            Log::error('[ArcamaxService]: ' . $exception->getMessage());
        }
    }

    /**
     * @param $postcode
     * @return mixed|null
     */
    public function sendUser(array $requestData, int|string $sourceId)
    {
        $this->sendRequest('POST', 'cgi-bin/autosub', [], [
            'email' => $requestData['email'] ?? '',
            'source' => $sourceId,
            'ipaddr' => $requestData['ip'] ?? '',
            'ts' => Carbon::now('UTC')->timestamp,
            'Fname' => $requestData['first_name'] ?? ($requestData['name'] ?? ''),
            'scextcode' => ''
        ]);

    }
}
