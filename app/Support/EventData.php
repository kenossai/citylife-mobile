<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class EventData
{
    public static function all(): array
    {
        $remote = ContentApi::get('events');

        if ($remote !== null) {
            return collect($remote)->mapWithKeys(fn (array $event) => [$event['id'] => self::fromApi($event)])->all();
        }

        $about = 'Join us for a time of worship, teaching and fellowship. Everyone is welcome, so bring a friend and come expecting.';

        return [
            1 => ['id' => 1, 'month' => 'Jan', 'day' => 5, 'date' => 'Sunday, 5 January', 'title' => 'Bible School International', 'time' => '10:00 AM', 'end' => '12:00 PM', 'place' => 'Main Church', 'address' => 'City Life Church, Sheffield', 'category' => 'Study', 'about' => $about],
            2 => ['id' => 2, 'month' => 'Jan', 'day' => 7, 'date' => 'Tuesday, 7 January', 'title' => 'Bible Study', 'time' => '6:30 PM', 'end' => '8:00 PM', 'place' => 'Main Church', 'address' => 'City Life Church, Sheffield', 'category' => 'Study', 'about' => $about],
            3 => ['id' => 3, 'month' => 'Jan', 'day' => 11, 'date' => 'Saturday, 11 January', 'title' => 'Sunday Service', 'time' => '10:30 AM', 'end' => '12:30 PM', 'place' => 'Main Church', 'address' => 'City Life Church, Sheffield', 'category' => 'Services', 'about' => $about],
            4 => ['id' => 4, 'month' => 'Jan', 'day' => 14, 'date' => 'Tuesday, 14 January', 'title' => 'Youth Night', 'time' => '7:00 PM', 'end' => '9:00 PM', 'place' => 'Youth Hall', 'address' => 'City Life Church, Sheffield', 'category' => 'Youth', 'about' => $about],
            5 => ['id' => 5, 'month' => 'Jan', 'day' => 18, 'date' => 'Saturday, 18 January', 'title' => 'Community Lunch', 'time' => '12:30 PM', 'end' => '2:00 PM', 'place' => 'Church Cafe', 'address' => 'City Life Church, Sheffield', 'category' => 'Community', 'about' => $about],
        ];
    }

    public static function find(int $id): ?array
    {
        if (ContentApi::isConfigured()) {
            $event = ContentApi::get("events/{$id}");

            return $event ? self::fromApi($event) : null;
        }

        return static::all()[$id] ?? null;
    }

    /**
     * @param  array<string, mixed>  $event
     * @return array<string, mixed>
     */
    private static function fromApi(array $event): array
    {
        $startsAt = Carbon::parse($event['starts_at']);

        return [
            'id' => $event['id'],
            'month' => $startsAt->format('M'),
            'day' => (int) $startsAt->format('j'),
            'date' => $startsAt->format('l, j F'),
            'title' => $event['title'],
            'time' => $startsAt->format('g:i A'),
            'end' => '',
            'place' => $event['location'] ?: 'City Life Church',
            'address' => '',
            'category' => $event['category'] ?: 'Events',
            'about' => $event['description'] ?? '',
            'image' => $event['image_url'] ?? null,
        ];
    }
}
