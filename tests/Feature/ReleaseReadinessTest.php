<?php

namespace Tests\Feature;

use App\Livewire\Home;
use App\Livewire\Profile;
use App\Support\ContentApi;
use App\Support\EventData;
use App\Support\MemberSession;
use App\Support\NotificationData;
use App\Support\SeriesData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ReleaseReadinessTest extends TestCase
{
    private const MEMBER = [
        'id' => 1, 'first_name' => 'Ada', 'last_name' => 'Obi', 'name' => 'Ada Obi', 'email' => 'ada@example.com',
        'phone' => null, 'membership_number' => 'CL1', 'membership_status' => 'member', 'member_since' => '2024-03-15',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    private function website(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
    }

    public function test_events_show_the_end_time_and_address_from_the_website(): void
    {
        $this->website();
        Http::fake(['site.test/api/v1/events' => Http::response(['data' => [[
            'id' => 3, 'title' => 'Conference', 'location' => 'Main Hall', 'address' => '1 South Parade, Sheffield', 'category' => 'Special',
            'description' => null, 'starts_at' => '2027-07-10T19:00:00+01:00', 'ends_at' => '2027-07-10T21:30:00+01:00', 'image_url' => null,
        ]]])]);

        $event = EventData::all()[3];

        $this->assertSame('7:00 PM', $event['time']);
        $this->assertSame('9:30 PM', $event['end']);
        $this->assertSame('1 South Parade, Sheffield', $event['address']);
    }

    public function test_an_unreachable_website_never_shows_sample_content_once_connected(): void
    {
        $this->website();
        Http::fake(['site.test/*' => Http::response([], 500)]);

        $this->assertSame([], EventData::all());
        $this->assertSame([], SeriesData::all());
        $this->assertSame([], NotificationData::all());
        $this->assertNull(EventData::find(1));
    }

    public function test_the_built_in_sample_content_still_serves_demo_builds_with_no_website(): void
    {
        $this->assertCount(5, EventData::all());
        $this->assertNotEmpty(SeriesData::all());
        $this->assertCount(2, NotificationData::all());
    }

    public function test_the_home_screen_hides_the_live_card_and_series_when_the_website_is_unreachable(): void
    {
        $this->website();
        Http::fake(['site.test/*' => Http::response([], 500)]);

        Livewire::test(Home::class)
            ->assertDontSee('LIVE NOW')
            ->assertDontSee('Latest Series')
            ->assertSee('No upcoming events right now');
    }

    public function test_the_last_good_content_is_still_used_when_the_website_goes_down(): void
    {
        $this->website();
        Http::fake(['site.test/api/v1/events' => Http::sequence()
            ->push(['data' => [['id' => 1, 'title' => 'Sunday Service', 'location' => null, 'category' => null, 'description' => null, 'starts_at' => '2027-07-10T10:30:00+01:00']]])
            ->pushStatus(500),
        ]);

        $this->assertSame('Sunday Service', EventData::all()[1]['title']);

        ContentApi::refresh();

        $this->assertSame('Sunday Service', EventData::all()[1]['title']);
    }

    public function test_refreshing_makes_the_next_read_ask_the_website_again(): void
    {
        $this->website();
        Http::fake(['site.test/api/v1/events' => Http::sequence()
            ->push(['data' => [['id' => 1, 'title' => 'Old', 'location' => null, 'category' => null, 'description' => null, 'starts_at' => '2027-07-10T10:30:00+01:00']]])
            ->push(['data' => [['id' => 1, 'title' => 'New', 'location' => null, 'category' => null, 'description' => null, 'starts_at' => '2027-07-10T10:30:00+01:00']]]),
        ]);

        $this->assertSame('Old', EventData::all()[1]['title']);
        $this->assertSame('Old', EventData::all()[1]['title']);

        ContentApi::refresh();

        $this->assertSame('New', EventData::all()[1]['title']);
        Http::assertSentCount(2);
    }

    public function test_a_page_load_with_refresh_clears_the_content_cache_but_a_normal_one_does_not(): void
    {
        $this->website();
        Http::fake(['site.test/*' => Http::response(['data' => []])]);

        $eventRequests = fn () => Http::recorded(fn ($request) => str_ends_with($request->url(), '/api/v1/events'))->count();

        $this->get(route('events'));
        $this->get(route('events'));
        $this->assertSame(1, $eventRequests());

        $this->get(route('events', ['refresh' => 1]));
        $this->assertSame(2, $eventRequests());
    }

    public function test_sign_out_of_all_devices_tells_the_website_and_forgets_the_member_here(): void
    {
        $this->website();
        session()->put(['citylife.member_token' => 'tok', 'citylife.member' => self::MEMBER]);
        Http::fake([
            'site.test/api/v1/auth/logout-all' => Http::response(['data' => null]),
            'site.test/api/v1/me/preferences' => Http::response(['data' => ['email_newsletters' => false, 'sms_updates' => false]]),
        ]);

        Livewire::test(Profile::class)->call('signOutEverywhere')->assertRedirect(route('home'));

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/auth/logout-all') && $request->hasHeader('Authorization', 'Bearer tok'));
        $this->assertFalse(MemberSession::check());
    }
}
