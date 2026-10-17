<?php

namespace App\Support;

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
            return ['type' => 'youtube', 'src' => 'https://www.youtube-nocookie.com/embed/'.$match[1].'?playsinline=1&rel=0'];
        }

        $path = strtolower((string) parse_url($url, PHP_URL_PATH));

        return match (true) {
            (bool) preg_match('/\.(mp4|m4v|mov|webm)$/', $path) => ['type' => 'video', 'src' => $url],
            (bool) preg_match('/\.(mp3|m4a|aac|wav)$/', $path) => ['type' => 'audio', 'src' => $url],
            default => ['type' => 'link', 'src' => $url],
        };
    }
}
