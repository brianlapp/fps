<?php

namespace App\Services;


use App\Helpers\MailHelper;
use App\Http\Controllers\LandingController;
use App\Models\EmailTemplate;
use App\Models\User;
use Bcismariu\Ongage\Api\Contacts;
use Bcismariu\Ongage\Ongage;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Soundasleep\Html2Text;
use stringEncode\Exception;

class OngageService
{

    /**
     * @var $client Ongage
     */
    private Ongage $client;

    /**
     * @var string
     */
    private string $listId;

    /**
     * @var string
     */
    private $espConnectionId;

    public function __construct()
    {
        $this->client = new Ongage(
            config('services.ongage.username'),
            config('services.ongage.password'),
            config('services.ongage.account_code')
        );
        $this->listId = config('services.ongage.list_id');
        $this->espConnectionId = config('services.ongage.esp_connection_id');

        $this->client->setDefaultListId($this->listId);
    }

    /**
     * @param array $data
     * @param $listId
     * @return bool
     */
    public function addContact(array $data, $listId = null)
    {
        if (!empty($listId)) {
            $this->client->setDefaultListId($listId);
        }

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
                Log::channel('ongage')->error('[addContact.nameParsing] ' . $exception->getMessage());
            }
        }
        Log::channel('ongage')->error($data);
        try {
            $contacts = new Contacts($this->client);
            $contacts->add([
                'overwrite' => true,
                'email' => $data['email'],
                'fields' => Arr::except($data, ['email', 'arcamax'])
            ]);


            if (!empty($data['arcamax'])) {
                $arcamax = new ArcamaxService();
                foreach ($data['arcamax'] as $sourceId) {
                    $arcamax->sendUser($data, $sourceId);
                }

            }

            return true;
        } catch (\Throwable $exception) {
            Log::channel('ongage')->error('[addContact] ' . $exception->getMessage());

            return false;
        }

    }

    /**
     * @param $email
     * @param $listId
     */
    public function removeContact($email, $listId = null)
    {
        try {
            $this->client->call('POST', 'v2/contacts/change_status', [
                'list_id' => $listId ?? $this->listId,
                'change_to' => 'remove',
                'emails' => [$email]
            ]);
        } catch (\Throwable $exception) {
            Log::channel('ongage')->error($exception->getMessage());
        }

    }

    /**
     * @param $email
     * @param $listId
     */
    public function unsubscribe($email, $listId = null)
    {
        try {
            $this->client->call('POST', 'v2/contacts/change_status', [
                'list_id' => $listId ?? $this->listId,
                'change_to' => 'unsubscribe',
                'emails' => [$email]
            ]);
        } catch (\Throwable $exception) {
            Log::channel('ongage')->error($exception->getMessage());
        }

    }

    /**
     * @param string $emailId
     * @param string $segmentId
     * @param string $scheduledAt
     * @return mixed
     * @throws \Throwable
     */
    public function createDailyCampaign(string $emailId, string $segmentId, string $scheduledAt): mixed
    {
        try {
            $response = $this->client->call('POST', 'mailings', [
                'name' => config('app.name') . ' Daily Campaign ' . Carbon::now()->format('Y-m-d'),
                'description' => 'Daily Campaign',
                'email_message' => [$emailId],
                'segments' => [$segmentId],
                'distribution' => [
                    [
                        'esp_connection_id' => $this->espConnectionId,
                        'percent' => '100'
                    ]
                ],
                'schedule_date' => $scheduledAt,
                'pre_process' => true,
//                'is_test' => true,
//                'recipients' => [
//                    'marjan.pahor.86@gmail.com',
//                ]
            ]);

            return $response;
        } catch (\Throwable $exception) {
            Log::channel('ongage')->error($exception->getMessage());

            throw  $exception;
        }
    }
}
