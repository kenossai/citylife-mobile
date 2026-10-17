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
    public static function get(string $path): ?array
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
                Cache::put($key.':fresh', $data, self::FRESH_SECONDS);
                Cache::forever($key.':last', $data);

                return $data;
            }
        } catch (Throwable) {
            //
        }

        $last = Cache::get($key.':last');

        return is_array($last) ? $last : null;
    }
}
