<?php

namespace Tests\Feature;

use App\Support\EventData;
use App\Support\SeriesData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContentApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_hardcoded_content_is_used_when_no_website_is_configured(): void
    {
        Http::fake();

        $this->assertCount(5, EventData::all());

        Http::assertNothingSent();
    }

    public function test_events_are_mapped_from_the_website(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/events' => Http::response(['data' => [[
            'id' => 7,
            'title' => 'Youth Night',
            'description' => 'Games and worship',
            'location' => 'Youth Hall',
            'category' => 'Youth',
            'starts_at' => '2026-11-14T19:00:00+00:00',
        ]]])]);

        $event = EventData::find(7) ?? EventData::all()[7];

        $this->assertSame('Youth Night', $event['title']);
        $this->assertSame('Nov', $event['month']);
        $this->assertSame(14, $event['day']);
        $this->assertSame('7:00 PM', $event['time']);
        $this->assertSame('Youth Hall', $event['place']);
    }

    public function test_series_are_mapped_with_minutes_from_duration(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/series' => Http::response(['data' => [[
            'slug' => 'faith-journey',
            'title' => 'Faith Journey',
            'description' => null,
            'color' => '#374151',
            'sermons' => [
                ['title' => 'Walking in Faith', 'speaker' => 'Pastor John', 'duration' => '45:00', 'preached_at' => '2026-10-04', 'video_url' => null],
            ],
        ]]])]);

        $series = SeriesData::all()['faith-journey'];

        $this->assertSame('Faith Journey', $series['name']);
        $this->assertSame(45, $series['messages'][0]['minutes']);
        $this->assertSame('Pastor John', $series['messages'][0]['pastor']);
    }

    public function test_last_good_copy_is_served_when_the_website_is_down(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fakeSequence()
            ->push(['data' => [['id' => 1, 'title' => 'Sunday Service', 'location' => null, 'category' => null, 'description' => null, 'starts_at' => '2026-11-01T10:30:00+00:00']]])
            ->pushStatus(500);

        $this->assertSame('Sunday Service', EventData::all()[1]['title']);

        Cache::forget('citylife-api:events:fresh');

        $this->assertSame('Sunday Service', EventData::all()[1]['title']);
    }
}
