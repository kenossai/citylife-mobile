<?php

namespace Tests\Feature;

use App\Livewire\Home;
use App\Support\ContentApi;
use App\Support\EventData;
use App\Support\NotificationData;
use App\Support\SeriesData;
use App\Support\SermonMedia;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
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

        ContentApi::forget('events');

        $this->assertSame('Sunday Service', EventData::all()[1]['title']);
    }

    public function test_home_shows_the_verse_from_the_website(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/home' => Http::response(['data' => ['live' => null, 'verse' => ['text' => 'Be still and know.', 'reference' => 'Psalm 46:10']]]), 'site.test/*' => Http::response(['data' => []])]);

        $this->get(route('home'))->assertSee('Be still and know.')->assertSee('Psalm 46:10');
    }

    public function test_home_keeps_the_default_verse_when_the_website_has_none(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/home' => Http::response(['data' => ['live' => null, 'verse' => null]]), 'site.test/*' => Http::response(['data' => []])]);

        $this->get(route('home'))->assertSee('Matthew 18:20');
    }

    public function test_event_time_keeps_the_church_timezone_offset(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/events' => Http::response(['data' => [[
            'id' => 3, 'title' => 'Summer Night', 'location' => null, 'category' => null, 'description' => null,
            'starts_at' => '2027-07-10T19:00:00+01:00', 'image_url' => 'https://site.test/storage/e.png',
        ]]])]);

        $event = EventData::all()[3];

        $this->assertSame('7:00 PM', $event['time']);
        $this->assertSame('https://site.test/storage/e.png', $event['image']);
    }

    public function test_announcements_become_notifications(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/announcements' => Http::response(['data' => [[
            'id' => 4, 'title' => 'Car park closed', 'message' => 'Use the side entrance.', 'link_url' => null, 'published_at' => '2026-10-17T09:00:00+01:00',
        ]]])]);

        $notifications = NotificationData::all();

        $this->assertSame(['announcement-4'], array_keys($notifications));
        $this->assertSame('17 Oct 2026', $notifications['announcement-4']['time']);
        $this->assertSame(1, NotificationData::unreadCount([]));
        $this->assertSame(0, NotificationData::unreadCount(['announcement-4']));
    }

    public function test_live_card_follows_the_website(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/home' => Http::response(['data' => ['live' => ['title' => 'Midweek Service', 'speaker' => 'Pastor Ann'], 'verse' => null]]), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertSee('LIVE NOW')->assertSee('Midweek Service')->assertSee('with Pastor Ann');
    }

    public function test_live_card_is_hidden_when_nothing_is_live(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/home' => Http::response(['data' => ['live' => null, 'verse' => null]]), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertDontSee('LIVE NOW');
    }

    public function test_sermon_media_detects_how_to_play_a_url(): void
    {
        $this->assertSame('https://www.youtube-nocookie.com/embed/dQw4w9WgXcQ?playsinline=1&rel=0', SermonMedia::from('https://www.youtube.com/watch?v=dQw4w9WgXcQ')['src']);
        $this->assertSame('youtube', SermonMedia::from('https://youtu.be/dQw4w9WgXcQ')['type']);
        $this->assertSame('video', SermonMedia::from('https://cdn.test/a.mp4?x=1')['type']);
        $this->assertSame('audio', SermonMedia::from('https://cdn.test/a.mp3')['type']);
        $this->assertSame('link', SermonMedia::from('https://vimeo.com/123')['type']);
        $this->assertNull(SermonMedia::from(null));
    }

    public function test_player_embeds_the_website_video(): void
    {
        config(['services.citylife.url' => 'https://site.test']);

        Http::fake(['site.test/api/v1/series/faith-journey' => Http::response(['data' => [
            'slug' => 'faith-journey', 'title' => 'Faith Journey', 'description' => null, 'color' => '#374151',
            'sermons' => [['title' => 'Walking in Faith', 'speaker' => 'Pastor John', 'duration' => '45:00', 'preached_at' => '2026-10-04', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']],
        ]])]);

        $this->get(route('sermons.play', ['faith-journey', 1]))
            ->assertOk()
            ->assertSee('youtube-nocookie.com/embed/dQw4w9WgXcQ', false)
            ->assertDontSee('Back 15 seconds');
    }
}
