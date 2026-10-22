<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class NotificationData
{
    /** @return array<string, array{id: string, title: string, message: string, time: string, icon: string, link: ?string}> */
    public static function all(): array
    {
        $remote = ContentApi::get('announcements');

        if ($remote !== null) {
            return collect($remote)->mapWithKeys(fn (array $announcement) => [
                "announcement-{$announcement['id']}" => [
                    'id' => "announcement-{$announcement['id']}",
                    'title' => $announcement['title'],
                    'message' => $announcement['message'],
                    'time' => Carbon::parse($announcement['published_at'])->format('j M Y'),
                    'icon' => 'megaphone',
                    'link' => $announcement['link_url'] ?? null,
                ],
            ])->all();
        }

        if (ContentApi::isConfigured()) {
            return [];
        }

        return [
            'sunday-service' => [
                'id' => 'sunday-service',
                'title' => 'Sunday gathering',
                'message' => 'Join us this Sunday for worship, teaching and time together.',
                'time' => 'Church update',
                'icon' => 'calendar',
                'link' => null,
            ],
            'welcome' => [
                'id' => 'welcome',
                'title' => 'Welcome to City Life',
                'message' => 'We are glad you are here. Find a place to connect and grow.',
                'time' => 'Welcome',
                'icon' => 'heart',
                'link' => null,
            ],
        ];
    }

    /** @param array<int, string> $readIds */
    public static function unreadCount(array $readIds): int
    {
        return count(array_diff(array_keys(static::all()), $readIds));
    }
}
