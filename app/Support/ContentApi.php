<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class ContentApi
{
    private const FRESH_SECONDS = 300;

    private const VERSION_KEY = 'citylife-api:version';

    private const DOWN_KEY = 'citylife-api:down';

    /** How long to stop asking a website that did not answer, so every screen does not wait for it again. */
    private const DOWN_SECONDS = 30;

    private const CONNECT_TIMEOUT_SECONDS = 2;

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

        if (is_array($fresh = Cache::get(self::freshKey($path)))) {
            return $fresh;
        }

        if (! self::isDown()) {
            try {
                $response = Http::connectTimeout(self::CONNECT_TIMEOUT_SECONDS)
                    ->timeout(5)
                    ->acceptJson()
                    ->get(rtrim(config('services.citylife.url'), '/').'/api/v1/'.ltrim($path, '/'));

                if ($response->successful() && is_array($data = $response->json('data'))) {
                    Cache::put(self::freshKey($path), $data, $freshSeconds);
                    Cache::forever($key.':last', $data);

                    return $data;
                }

                if ($response->serverError()) {
                    self::markDown();
                }
            } catch (Throwable) {
                self::markDown();
            }
        }

        $last = Cache::get($key.':last');

        return is_array($last) ? $last : null;
    }

    public static function forget(string $path): void
    {
        Cache::forget(self::freshKey($path));
    }

    /**
     * Make every cached page of content count as stale so the next read asks the website again. The last good copies stay for offline use.
     */
    public static function refresh(): void
    {
        Cache::forever(self::VERSION_KEY, self::version() + 1);
        Cache::forget(self::DOWN_KEY);
    }

    public static function isDown(): bool
    {
        return Cache::has(self::DOWN_KEY);
    }

    private static function markDown(): void
    {
        Cache::put(self::DOWN_KEY, true, self::DOWN_SECONDS);
    }

    private static function version(): int
    {
        return (int) Cache::get(self::VERSION_KEY, 1);
    }

    private static function freshKey(string $path): string
    {
        return 'citylife-api:'.$path.':fresh:'.self::version();
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

        if ($method === 'GET' && self::isDown()) {
            return ['ok' => false, 'status' => 0, 'data' => null, 'message' => 'We could not reach the church. Please check your connection and try again.', 'body' => null];
        }

        try {
            $request = Http::connectTimeout(self::CONNECT_TIMEOUT_SECONDS)->timeout(8)->acceptJson();

            if ($token) {
                $request = $request->withToken($token);
            }

            $response = $request->send($method, rtrim(config('services.citylife.url'), '/').'/api/v1/'.ltrim($path, '/'), $method === 'GET' ? ['query' => $payload] : ['json' => $payload]);
        } catch (Throwable) {
            self::markDown();

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
