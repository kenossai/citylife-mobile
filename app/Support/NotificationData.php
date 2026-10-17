<?php

namespace App\Support;

class NotificationData
{
    /** @return array<string, array{id: string, title: string, message: string, time: string, icon: string}> */
    public static function all(): array
    {
        return [
            'sunday-service' => [
                'id' => 'sunday-service',
                'title' => 'Sunday gathering',
                'message' => 'Join us this Sunday for worship, teaching and time together.',
                'time' => 'Church update',
                'icon' => 'calendar',
            ],
            'welcome' => [
                'id' => 'welcome',
                'title' => 'Welcome to City Life',
                'message' => 'We are glad you are here. Find a place to connect and grow.',
                'time' => 'Welcome',
                'icon' => 'heart',
            ],
        ];
    }

    /** @param array<int, string> $readIds */
    public static function unreadCount(array $readIds): int
    {
        return count(array_diff(array_keys(static::all()), $readIds));
    }
}
