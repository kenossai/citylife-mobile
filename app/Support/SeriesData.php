<?php

namespace App\Support;

use Illuminate\Support\Str;

class SeriesData
{
    /** @return array<string, array{name: string, slug: string, description: string, color: string, messages: array<int, array{title: string, pastor: string, minutes: int}>}> */
    public static function all(): array
    {
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
        return self::all()[$slug] ?? null;
    }
}
