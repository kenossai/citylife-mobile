<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class ContentApi
{
    private const FRESH_SECONDS = 300;

    public static function isConfigured(): bool
    {
        return filled(config('services.citylife.url'));
    }

    /**
     * Fetch a v1 endpoint, serving a recent cached copy when available and the last good copy when the website is unreachable.
     *
     * @return array<int|string, mixed>|null
     */
    public static function get(string $path, int $freshSeconds = self::FRESH_SECONDS): ?array
    {
        if (! self::isConfigured()) {
            return null;
        }

        $key = 'citylife-api:'.$path;

        if (is_array($fresh = Cache::get($key.':fresh'))) {
            return $fresh;
        }

        try {
            $response = Http::timeout(5)
                ->acceptJson()
                ->get(rtrim(config('services.citylife.url'), '/').'/api/v1/'.ltrim($path, '/'));

            if ($response->successful() && is_array($data = $response->json('data'))) {
                Cache::put($key.':fresh', $data, $freshSeconds);
                Cache::forever($key.':last', $data);

                return $data;
            }
        } catch (Throwable) {
            //
        }

        $last = Cache::get($key.':last');

        return is_array($last) ? $last : null;
    }

    public static function forget(string $path): void
    {
        Cache::forget('citylife-api:'.$path.':fresh');
    }

    /**
     * Send a write or member-only request. Never cached.
     *
     * @param  array<string, mixed>  $payload
     * @return array{ok: bool, status: int, data: mixed, message: ?string, body: mixed}
     */
    public static function send(string $method, string $path, array $payload = [], ?string $token = null): array
    {
        if (! self::isConfigured()) {
            return ['ok' => false, 'status' => 0, 'data' => null, 'message' => 'The church website is not configured.', 'body' => null];
        }

        try {
            $request = Http::timeout(8)->acceptJson();

            if ($token) {
                $request = $request->withToken($token);
            }

            $response = $request->send($method, rtrim(config('services.citylife.url'), '/').'/api/v1/'.ltrim($path, '/'), $method === 'GET' ? ['query' => $payload] : ['json' => $payload]);
        } catch (Throwable) {
            return ['ok' => false, 'status' => 0, 'data' => null, 'message' => 'We could not reach the church. Please check your connection and try again.', 'body' => null];
        }

        $message = $response->json('message');

        if ($response->status() === 422 && is_array($errors = $response->json('errors'))) {
            $message = collect($errors)->flatten()->first() ?? $message;
        }

        return [
            'ok' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json('data'),
            'body' => $response->json(),
            'message' => $response->successful() ? null : ($message ?: 'Something went wrong. Please try again.'),
        ];
    }
}
