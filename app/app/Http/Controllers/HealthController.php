<?php

namespace App\Http\Controllers;

use App\Exceptions\HealthCheckException;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Predis\Connection\ConnectionException;

/**
 * Class HealthController
 * @package App\Http\Controllers
 */
class HealthController extends Controller
{

    /**
     * Checks Application health
     *
     * @return JsonResponse
     */
    public function check(): JsonResponse
    {
        $debug = request()->has('debug');

        try {
            try {
                User::query()->count();
            } catch (\Throwable $e) {
                throw new HealthCheckException('DB connection is lost');
            }

            try {
                Queue::connection('redis')->size();
            } catch (\Throwable | ConnectionException $e) {
                throw new HealthCheckException('Redis connection is lost');
            }

            try {
                Storage::disk(config('media-library.disk_name'))->directories();
            } catch (\Throwable $e) {
                throw new HealthCheckException('Storage connection is lost');
            }
        } catch (\Throwable | ConnectionException $exception) {
            Log::channel('healthcheck')->error($exception->getMessage());

            return response()->json(['message' => $debug ? $exception->getMessage() : 'ERROR'], 502);
        }

        return response()->json(['message' => 'OK'], 200);
    }
}
