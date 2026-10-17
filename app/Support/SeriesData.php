<?php

namespace App\Support;

use Illuminate\Support\Str;

class SeriesData
{
    /** @return array<string, array{name: string, slug: string, description: string, color: string, messages: array<int, array{title: string, pastor: string, minutes: int}>}> */
    public static function all(): array
    {
        $remote = ContentApi::get('series');

        if ($remote !== null) {
            return collect($remote)->mapWithKeys(fn (array $series) => [$series['slug'] => self::fromApi($series)])->all();
        }

        $series = [
            [
                'name' => 'Faith Journey',
                'description' => 'A 6-week series exploring what it means to walk by faith',
                'color' => '#374151',
                'messages' => [
                    ['Introduction to Faith Journey', 45],
                    ['Building Strong Foundations', 42],
                    ['Walking in Faith', 45],
                    ['Faith in the Storm', 40],
                    ['Living with Purpose', 42],
                    ['Finishing Well', 44],
                ],
            ],
            [
                'name' => 'Prayer Life',
                'description' => 'Learning to pray with confidence, honesty and expectation',
                'color' => '#9a6b5a',
                'messages' => [
                    ['Why We Pray', 36],
                    ['The Power of Prayer', 38],
                    ['Praying with Persistence', 41],
                    ['Grace That Restores', 40],
                ],
            ],
        ];

        $all = [];
        foreach ($series as $item) {
            $slug = Str::slug($item['name']);
            $item['slug'] = $slug;
            $item['messages'] = array_map(fn (array $m) => ['title' => $m[0], 'pastor' => 'Pastor John Doe', 'minutes' => $m[1]], $item['messages']);
            $all[$slug] = $item;
        }

        return $all;
    }

    public static function find(string $slug): ?array
    {
        if (ContentApi::isConfigured()) {
            $series = ContentApi::get('series/'.rawurlencode($slug));

            return $series ? self::fromApi($series) : null;
        }

        return self::all()[$slug] ?? null;
    }

    /**
     * @param  array<string, mixed>  $series
     * @return array<string, mixed>
     */
    private static function fromApi(array $series): array
    {
        return [
            'name' => $series['title'],
            'slug' => $series['slug'],
            'description' => $series['description'] ?? '',
            'color' => $series['color'] ?: '#e85d26',
            'image' => $series['thumbnail_url'] ?? null,
            'messages' => array_map(fn (array $sermon) => [
                'title' => $sermon['title'],
                'pastor' => $sermon['speaker'] ?: 'City Life Church',
                'minutes' => self::minutes($sermon['duration'] ?? null),
                'date' => $sermon['preached_at'] ?? null,
                'video_url' => $sermon['video_url'] ?? null,
            ], $series['sermons'] ?? []),
        ];
    }

    private static function minutes(?string $duration): int
    {
        if ($duration === null) {
            return 0;
        }

        if (preg_match('/^(\d+):(\d+):(\d+)$/', $duration, $parts)) {
            return ((int) $parts[1] * 60) + (int) $parts[2];
        }

        if (preg_match('/^(\d+):(\d+)$/', $duration, $parts)) {
            return (int) $parts[1];
        }

        return preg_match('/\d+/', $duration, $parts) ? (int) $parts[0] : 0;
    }
}
