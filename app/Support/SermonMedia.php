<?php

namespace App\Support;

use Native\Mobile\Facades\System;

class SermonMedia
{
    /**
     * Describe how a sermon's video URL should be played.
     *
     * @return array{type: string, src: string}|null
     */
    public static function from(?string $url): ?array
    {
        if (blank($url)) {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|live/|shorts/)|youtu\.be/)([\w-]{11})~', $url, $match)) {
            return ['type' => 'youtube', 'src' => self::youtubePlayerUrl($match[1])];
        }

        $path = strtolower((string) parse_url($url, PHP_URL_PATH));

        return match (true) {
            (bool) preg_match('/\.(mp4|m4v|mov|webm)$/', $path) => ['type' => 'video', 'src' => $url],
            (bool) preg_match('/\.(mp3|m4a|aac|wav)$/', $path) => ['type' => 'audio', 'src' => $url],
            default => ['type' => 'link', 'src' => $url],
        };
    }

    /**
     * Where tapping a sermon should go. On the phone a YouTube sermon opens the in-app player straight away, because the web screens
     * cannot embed YouTube (the app shell sends it to the browser); anywhere else it opens the sermon page.
     *
     * @return array{url: string, native: bool}
     */
    public static function watchTarget(int $id, ?string $videoUrl): array
    {
        if ((self::from($videoUrl)['type'] ?? null) === 'youtube' && System::isMobile()) {
            return ['url' => "/native/sermon/{$id}", 'native' => true];
        }

        return ['url' => route('sermons.watch', $id), 'native' => false];
    }

    /**
     * YouTube answers a web view that sends no referrer with "error 153", so the video is framed by a page on the church website.
     */
    private static function youtubePlayerUrl(string $videoId): string
    {
        $site = rtrim((string) config('services.citylife.url'), '/');

        return $site !== ''
            ? "{$site}/app/youtube/{$videoId}"
            : "https://www.youtube.com/embed/{$videoId}?playsinline=1&rel=0";
    }
}
