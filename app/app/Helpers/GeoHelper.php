<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class GeoHelper
{
    /**
     * Function to get user ip from load balancer.
     *
     * @return string ip
     */
    public static function getIp()
    {
        foreach (['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'] as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);

                    return $ip;
                }
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public static function getGeoPayload(): array
    {
        $defaultResponse = config('geoip.default_location');
        $ip = self::getIp();
        $default = [
            'ip' => $ip,
            'country' => $defaultResponse['iso_code'],
            'state' => $defaultResponse['state']
        ];

        try {
            $lookup = geoip()->getLocation($ip);

            return [
                'ip' => $ip,
                'country' => $lookup->iso_code,
                'state' => $lookup->state
            ];
        } catch (\Throwable $exception) {
            Log::channel('ip-lookup')->error($exception->getMessage());
            // We return default GEO info from config by default to make sure that 3rd party bug won't block the site for our users
            return $default;
        }
    }
}
