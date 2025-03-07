<?php

namespace App\Services;


use App\Helpers\MailHelper;
use App\Http\Controllers\LandingController;
use App\Models\EmailTemplate;
use App\Models\User;
use Bcismariu\Ongage\Api\Contacts;
use Bcismariu\Ongage\Ongage;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Soundasleep\Html2Text;
use stringEncode\Exception;

class BeeHiivService
{

    const LOG_CHANNEL = 'bee-hiiv';
    private Client $client;

    private string $publicationId;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.beehiiv.com/v' . config('services.beehiiv.api_version') . '/',
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . config('services.beehiiv.api_key'),
            ],
            'verify' => false
        ]);
        $this->publicationId = config('services.beehiiv.publication_id');
    }

    /**
     * @param array $data
     * @return bool
     */
    public function addContact(array $data, bool $sendWelcome = true): bool
    {
        if (isset($data['name'])) {
            $data['first_name'] = '';
            $data['last_name'] = '';
            try {
                $n = explode(' ', $data['name']);

                if (isset($n[0])) {
                    $data['first_name'] = $n[0];
                }
                if (isset($n[1])) {
                    $data['last_name'] = $n[1];
                }
            } catch (\Throwable $exception) {
                Log::channel(self::LOG_CHANNEL)->error('[addContact.nameParsing] ' . $exception->getMessage());
            }
        }

        try {
            $response = $this->client->post("publications/$this->publicationId/subscriptions", [
                'json' => array_filter([
                    'email' => $data['email'],
                    'utm_source' => $data['utm_source'] ?? null,
                    'utm_medium' => $data['utm_medium'] ?? null,
                    'utm_campaign' => $data['utm_campaign'] ?? null,
                    'referring_site' => url(''),
                    'send_welcome_email' => $sendWelcome,
                    'custom_fields' => [
                        [
                            'name' => 'First Name',
                            'value' => $data['first_name'] ?? $data['name']
                        ],
                        [
                            'name' => 'Last Name',
                            'value' => $data['last_name'] ?? ''
                        ],
                        [
                            'name' => 'Country_c',
                            'value' => $data['country'] ?? ''
                        ],
                        [
                            'name' => 'Province',
                            'value' => $data['state'] ?? ''
                        ],
                        [
                            'name' => 'Postal Code',
                            'value' => $data['postcode'] ?? ''
                        ],
                        [
                            'name' => 'registration_type',
                            'value' => $data['registration_type'] ?? 'membership'
                        ],
                        [
                            'name' => 'registration_path',
                            'value' => $data['registration_path'] ?? 'signup'
                        ]
                    ]
                ])
            ]);

            return true;
        } catch (\Throwable $exception) {
            if (method_exists($exception, 'getResponse')) {
                Log::channel(self::LOG_CHANNEL)->error('[addContact] ' . $exception->getResponse()->getBody()->getContents());
            } else {
                Log::channel(self::LOG_CHANNEL)->error('[addContact] ' . $exception->getMessage());
            }

            return false;
        }

    }

    /**
     * @param string $email
     * @param array $data
     * @return bool
     */
    public function updateContact(string $email, array $data): bool
    {
        $customFields = [];
        $possibleFields = [
            'postcode' => 'Postal Code',
            'phone' => 'Phone',
            'child_dob' => 'Baby\'s Birthday',
            'embark' => 'embark',
            'cst' => 'cst',
            'was_in_embark_before' => 'was_in_embark_before',
            'embark_standalone' => 'embark_standalone',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
        ];

        try {
            foreach ($possibleFields as $key => $customField) {
                if (array_key_exists($key, $data)) {
                    $customFields[] = [
                        'name' => $customField,
                        'value' => $data[$key] ?: ''
                    ];
                }
            }
            $contact = $this->getContactByEmail($email);
            if (empty($contact)) {
                throw new \Exception('No Subscription found for email ' . $email);
            }

            $this->client->patch("publications/$this->publicationId/subscriptions/$contact->id", [
                'json' => [
                    'email' => $data['email'] ?? $email,
                    'custom_fields' => $customFields
                ]
            ]);

            return true;
        } catch (\Throwable $exception) {
            if (method_exists($exception, 'getResponse')) {
                Log::channel(self::LOG_CHANNEL)->error('[updateContact] ' . $exception->getResponse()->getBody()->getContents());
            } else {
                Log::channel(self::LOG_CHANNEL)->error('[updateContact] ' . $exception->getMessage());
            }

            return false;
        }

    }

    /**
     * @param string $email
     * @return ?object
     */
    public function getContactByEmail(string $email): ?object
    {
        try {
            $response = $this->client->get("publications/$this->publicationId/subscriptions/by_email/$email");
            $result = json_decode($response->getBody()->getContents());

            return $result->data;

        } catch (\Throwable $exception) {
            if (method_exists($exception, 'getResponse')) {
                Log::channel(self::LOG_CHANNEL)->error('[getContactByEmail] ' . $exception->getResponse()->getBody()->getContents());
            } else {
                Log::channel(self::LOG_CHANNEL)->error('[getContactByEmail] ' . $exception->getMessage());
            }

            return null;
        }

    }

    /**
     * @param string $email
     * @param array $tags
     * @return bool
     */
    public function addTags(string $email, array $tags): bool
    {

        try {
            $contact = $this->getContactByEmail($email);
            if (empty($contact)) {
                throw new \Exception('No Subscription found for email ' . $email);
            }

            $this->client->post("publications/$this->publicationId/subscriptions/$contact->id/tags", [
                'json' => [
                    'tags' => $tags
                ]
            ]);

            return true;
        } catch (\Throwable $exception) {
            if (method_exists($exception, 'getResponse')) {
                Log::channel(self::LOG_CHANNEL)->error('[addTags] ' . $exception->getResponse()->getBody()->getContents());
            } else {
                Log::channel(self::LOG_CHANNEL)->error('[addTags] ' . $exception->getMessage());
            }

            return false;
        }

    }
}
