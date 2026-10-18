<?php

namespace App\Support;

class ChurchData
{
    /**
     * Church details from the website, falling back to what the app already knew when the website is unreachable or not configured.
     *
     * @return array<string, mixed>
     */
    public static function get(): array
    {
        $remote = ContentApi::get('church');

        return array_replace_recursive(self::defaults(), array_filter($remote ?? [], fn ($value) => $value !== null && $value !== []));
    }

    /** @return array<string, mixed> */
    private static function defaults(): array
    {
        return [
            'name' => 'City Life International Church',
            'email' => 'admin1@citylifecc.com',
            'phone' => '0114 272 8243',
            'address' => '1 South Parade, Shalesmoor, Sheffield, S3 8SS',
            'landmark' => null,
            'directions_url' => 'https://www.google.com/maps/dir/?api=1&destination='.rawurlencode('1 South Parade, Shalesmoor, Sheffield, S3 8SS'),
            'service_times' => [
                ['day' => 'Sunday', 'times' => ['9:00 AM - Adult Bible Study', '10:30 AM - Main Service']],
                ['day' => 'Thursday', 'times' => ['7:30 PM - Prayer Meeting']],
            ],
            'giving' => ['card_url' => null, 'paypal_url' => null, 'bank' => [], 'in_person' => null],
            'faqs' => [
                ['question' => 'When does the church meet?', 'answer' => 'Sunday worship is from 10:30 am to 12:00 pm. We would love to welcome you.'],
                ['question' => 'Where can I find City Life?', 'answer' => '1 South Parade, Shalesmoor, Sheffield, S3 8SS.'],
            ],
            'share' => ['ios_url' => null, 'android_url' => null, 'message' => 'Join me at City Life International Church in Sheffield.'],
        ];
    }
}
