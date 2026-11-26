<?php

namespace Tests\Feature;

use App\Livewire\Home;
use App\Livewire\Sermons;
use App\NativeComponents\NativeEventDetail;
use App\NativeComponents\NativeEvents;
use App\NativeComponents\NativePrayerRequest;
use App\NativeComponents\NativeSermonPlayer;
use App\Support\LibraryStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Native\Mobile\System;
use Native\Mobile\Testing\FakeBridge;
use Native\Mobile\Testing\Native;
use Tests\TestCase;

class NativeScreensTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);

        // NativePHP remembers the detected platform in a static property; forget it so one test's "iPhone" does not leak into the next.
        foreach (['platform', 'lastFailedProbe'] as $property) {
            (new \ReflectionProperty(System::class, $property))->setValue(null, null);
        }
    }

    /** @return array<string, mixed> */
    private function event(int $id, string $title, array $overrides = []): array
    {
        return [
            'id' => $id, 'title' => $title, 'description' => 'Games and worship', 'location' => 'Youth Hall', 'address' => '1 South Parade',
            'category' => 'Youth', 'starts_at' => '2027-07-10T19:00:00+01:00', 'ends_at' => '2027-07-10T21:00:00+01:00',
            'image_url' => null, 'requires_payment' => false, ...$overrides,
        ];
    }

    private function website(array $extra = []): void
    {
        Http::fake($extra + [
            'site.test/api/v1/events/3' => Http::response(['data' => $this->event(3, 'Conference', ['requires_payment' => true])]),
            'site.test/api/v1/events/4' => Http::response(['data' => $this->event(4, 'Youth Night')]),
            'site.test/api/v1/events' => Http::response(['data' => [$this->event(3, 'Conference', ['requires_payment' => true]), $this->event(4, 'Youth Night')]]),
            'site.test/api/v1/church' => Http::response(['data' => ['giving' => ['card_url' => 'https://pay.example/card', 'paypal_url' => 'https://pay.example/paypal', 'bank' => [], 'in_person' => null]]]),
            'site.test/*' => Http::response(['data' => []]),
        ]);
    }

    public function test_the_events_screen_lists_events_from_the_website_in_a_native_list(): void
    {
        $this->website();

        Native::test(NativeEvents::class)
            ->assertSee('Conference')
            ->assertSee('Youth Night')
            ->assertSee('7:00 PM · Youth Hall')
            ->assertElement('list')
            ->assertElement('list_item');
    }

    public function test_tapping_an_event_pushes_its_native_detail_screen(): void
    {
        $this->website();

        Native::test(NativeEvents::class)->call('open', 3)->assertNavigatedTo('/native/events/3');
    }

    public function test_pulling_down_asks_the_website_for_fresh_events(): void
    {
        $this->website(['site.test/api/v1/events' => Http::sequence()
            ->push(['data' => [$this->event(3, 'Old Conference')]])
            ->push(['data' => [$this->event(3, 'New Conference')]]),
        ]);

        Native::test(NativeEvents::class)
            ->assertSee('Old Conference')
            ->call('refreshEvents')
            ->assertSee('New Conference')
            ->assertDontSee('Old Conference');
    }

    public function test_an_unreachable_website_shows_a_retry_message_not_sample_events(): void
    {
        Http::fake(['site.test/*' => Http::response([], 500)]);

        Native::test(NativeEvents::class)
            ->assertSee("Can't load events right now")
            ->assertSee('Pull down to try again.')
            ->assertDontSee('Bible School International');
    }

    public function test_an_empty_list_says_there_are_no_events(): void
    {
        Http::fake(['site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeEvents::class)->assertSee('No upcoming events');
    }

    public function test_the_events_screen_can_return_to_the_web_app(): void
    {
        $this->website();

        Native::test(NativeEvents::class)->call('goHome')->assertExitedToWeb('/home');
    }

    public function test_the_detail_screen_shows_the_event(): void
    {
        $this->website();

        Native::test(NativeEventDetail::class, ['id' => 4])
            ->assertSee('Youth Night')
            ->assertSee('Youth Hall')
            ->assertSee('1 South Parade')
            ->assertSee('Games and worship')
            ->assertSee('7:00 PM')
            ->assertDontSee('Pay for this event');
    }

    public function test_a_paid_event_offers_the_payment_links_which_open_the_in_app_browser(): void
    {
        $this->website();

        Native::test(NativeEventDetail::class, ['id' => 3])
            ->assertSee('Pay for this event')
            ->assertSee('Pay by card')
            ->call('payByCard')
            ->assertNativeCalled('Browser.OpenInApp', fn (array $params) => $params['url'] === 'https://pay.example/card');
    }

    public function test_an_unknown_event_says_so_and_can_go_back(): void
    {
        Http::fake(['site.test/*' => Http::response([], 404)]);

        Native::test(NativeEventDetail::class, ['id' => 99])->assertSee("We can't find that event");
    }

    public function test_a_prayer_request_that_is_too_short_is_not_sent(): void
    {
        $this->website();

        Native::test(NativePrayerRequest::class)
            ->set('request', 'Too short')
            ->call('send')
            ->assertSee('Please write at least 10 characters.')
            ->assertSet('sent', false);

        Http::assertNotSent(fn ($request) => str_ends_with($request->url(), '/prayer-requests'));
    }

    public function test_a_prayer_request_is_sent_to_the_website_and_confirmed(): void
    {
        $this->website(['site.test/api/v1/prayer-requests' => Http::response(['data' => ['id' => 9]], 201)]);

        Native::test(NativePrayerRequest::class)
            ->set('name', 'Sam')
            ->set('category', 'Health')
            ->set('isPrivate', true)
            ->call('send', 'Please pray for my father.')
            ->assertSet('sent', true)
            ->assertSee('Thank you')
            ->assertSee('pastoral team')
            ->assertSet('request', '');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/prayer-requests')
            && $request['name'] === 'Sam' && $request['category'] === 'Health' && $request['request'] === 'Please pray for my father.' && $request['is_private'] === true);
    }

    public function test_a_website_error_keeps_the_text_and_shows_the_message(): void
    {
        $this->website(['site.test/api/v1/prayer-requests' => Http::response(['message' => 'Too many requests.'], 429)]);

        Native::test(NativePrayerRequest::class)
            ->set('request', 'Please keep praying for us.')
            ->call('send')
            ->assertSet('sent', false)
            ->assertSet('request', 'Please keep praying for us.')
            ->assertSee('Too many requests.');
    }

    public function test_another_request_can_be_started_after_sending(): void
    {
        $this->website(['site.test/api/v1/prayer-requests' => Http::response(['data' => ['id' => 9]], 201)]);

        Native::test(NativePrayerRequest::class)
            ->call('send', 'Please pray for our church.')
            ->assertSet('sent', true)
            ->call('again')
            ->assertSet('sent', false)
            ->assertSee('Send request');
    }

    /** @return array<string, mixed> */
    private function sermon(array $overrides = []): array
    {
        return [
            'id' => 11, 'title' => 'Seek First the Kingdom', 'speaker' => 'Pastor Sam', 'preached_at' => '2026-10-11', 'scripture' => ['Matthew 6:33'],
            'description' => 'About priorities', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'views' => 5, 'series' => null, ...$overrides,
        ];
    }

    public function test_a_youtube_sermon_plays_as_soon_as_it_opens_with_no_play_button(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => ['views' => 6]])]);

        $screen = Native::test(NativeSermonPlayer::class, ['id' => 11])
            ->assertSee('Seek First the Kingdom')
            ->assertSee('Pastor Sam')
            ->assertSee('Matthew 6:33')
            ->assertSee('About priorities')
            ->assertDontSee('Play sermon')
            ->assertDontSee('Close video')
            ->assertElement('webview', fn ($node) => str_contains(json_encode($node, JSON_UNESCAPED_SLASHES), 'site.test/app/youtube/dQw4w9WgXcQ?autoplay=1'));

        $screen->assertNativeNotCalled('Browser.OpenInApp');
    }

    public function test_a_long_description_can_be_expanded(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon(['description' => str_repeat('word ', 80).'THE END'])]), 'site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeSermonPlayer::class, ['id' => 11])
            ->assertSee('Read more')
            ->assertDontSee('THE END')
            ->call('toggleReadMore')
            ->assertSee('THE END')
            ->assertSee('Show less');
    }

    public function test_watching_counts_a_view(): void
    {
        Http::fake(['site.test/api/v1/sermons/11/view' => Http::response(['data' => ['views' => 6]]), 'site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeSermonPlayer::class, ['id' => 11]);

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/sermons/11/view'));
    }

    public function test_a_sermon_with_only_a_page_link_offers_an_explicit_button_instead_of_leaving_the_app(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon(['video_url' => 'https://vimeo.com/123'])]), 'site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeSermonPlayer::class, ['id' => 11])
            ->assertSee('Watch this message')
            ->assertNativeNotCalled('Browser.OpenInApp')
            ->call('openLink')
            ->assertNativeCalled('Browser.OpenInApp', fn (array $params) => $params['url'] === 'https://vimeo.com/123');
    }

    public function test_a_sermon_without_a_recording_says_so(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon(['video_url' => null])]), 'site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeSermonPlayer::class, ['id' => 11])->assertSee("isn't available yet");
    }

    public function test_a_sermon_that_cannot_be_loaded_can_be_closed(): void
    {
        Http::fake(['site.test/*' => Http::response([], 500)]);

        Native::test(NativeSermonPlayer::class, ['id' => 11])
            ->assertSee("We can't load this sermon right now")
            ->call('close')
            ->assertExitedToWeb('/app/return');
    }

    public function test_closing_or_pressing_back_returns_to_the_sermon_page(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => []])]);

        Native::test(NativeSermonPlayer::class, ['id' => 11])->call('close')->assertExitedToWeb('/app/return');
        Native::test(NativeSermonPlayer::class, ['id' => 11])->call('onBackPressed')->assertExitedToWeb('/app/return');
    }

    public function test_on_the_phone_the_sermon_page_links_to_the_native_player_instead_of_a_frame_the_shell_would_send_to_safari(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        FakeBridge::enable()->respondTo('Device.GetInfo', ['info' => json_encode(['platform' => 'ios'])]);
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => []])]);

        $this->get(route('sermons.watch', 11))
            ->assertOk()
            ->assertSee('href="/native/sermon/11"', false)
            ->assertSee('Watch sermon')
            ->assertDontSee('<iframe', false);
    }

    public function test_on_the_phone_a_youtube_sermon_in_the_list_opens_the_player_directly(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        FakeBridge::enable()->respondTo('Device.GetInfo', ['info' => json_encode(['platform' => 'ios'])]);
        Http::fake(['site.test/api/v1/sermons' => Http::response(['data' => [
            $this->sermon(['id' => 11]),
            $this->sermon(['id' => 12, 'title' => 'Audio only', 'video_url' => 'https://site.test/a.mp3']),
        ]])]);

        Livewire::test(Sermons::class)
            ->assertSeeHtml('href="/native/sermon/11"')
            ->assertDontSeeHtml('href="/native/sermon/12"')
            ->assertSeeHtml('href="'.route('sermons.watch', 12).'"');
    }

    public function test_in_a_desktop_browser_the_sermon_page_still_embeds_the_video(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => []])]);

        $this->get(route('sermons.watch', 11))->assertOk()->assertSee('<iframe', false)->assertDontSee('/native/sermon/11', false);
    }

    public function test_the_series_player_also_opens_the_native_player_on_the_phone(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        FakeBridge::enable()->respondTo('Device.GetInfo', ['info' => json_encode(['platform' => 'android'])]);
        Http::fake(['site.test/api/v1/series/faith' => Http::response(['data' => [
            'slug' => 'faith', 'title' => 'Faith', 'description' => null, 'color' => '#374151',
            'sermons' => [['id' => 21, 'title' => 'Walking in Faith', 'speaker' => 'P', 'duration' => '45:00', 'preached_at' => '2026-10-04', 'video_url' => 'https://youtu.be/dQw4w9WgXcQ']],
        ]]), 'site.test/*' => Http::response(['data' => []])]);

        $this->get(route('sermons.play', ['faith', 1]))->assertOk()->assertSee('href="/native/sermon/21"', false)->assertDontSee('<iframe', false);
    }

    /** @return array<string, mixed> */
    private function homeFeed(?array $live): array
    {
        return ['data' => ['live' => $live, 'verse' => null, 'events' => [], 'series' => []]];
    }

    public function test_the_live_card_opens_the_native_player_on_the_phone_for_a_youtube_stream(): void
    {
        FakeBridge::enable()->respondTo('Device.GetInfo', ['info' => json_encode(['platform' => 'ios'])]);
        Http::fake(['site.test/api/v1/home' => Http::response($this->homeFeed(['id' => 11, 'title' => 'Sunday Service', 'speaker' => 'Pastor Sam', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'])), 'site.test/*' => Http::response(['data' => []])]);

        $html = Livewire::test(Home::class)->assertSee('LIVE NOW')->assertSee('Sunday Service')->html();

        $this->assertMatchesRegularExpression('~<a href="/native/sermon/11"\s+class="watch">Watch</a>~', $html);
    }

    public function test_the_live_card_opens_the_sermon_page_when_not_on_the_phone(): void
    {
        Http::fake(['site.test/api/v1/home' => Http::response($this->homeFeed(['id' => 11, 'title' => 'Sunday Service', 'speaker' => null, 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ'])), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertSee(route('sermons.watch', 11), false)->assertDontSee('/native/sermon/11', false);
    }

    public function test_the_live_card_falls_back_to_the_sermon_list_when_there_is_nothing_to_play(): void
    {
        Http::fake(['site.test/api/v1/home' => Http::response($this->homeFeed(['id' => 11, 'title' => 'Sunday Service', 'speaker' => null, 'video_url' => null])), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertSee('LIVE NOW')->assertSee(route('sermons'), false)->assertDontSee(route('sermons.watch', 11), false);
    }

    public function test_a_vimeo_style_live_link_opens_the_sermon_page_not_the_native_youtube_player(): void
    {
        FakeBridge::enable()->respondTo('Device.GetInfo', ['info' => json_encode(['platform' => 'ios'])]);
        Http::fake(['site.test/api/v1/home' => Http::response($this->homeFeed(['id' => 11, 'title' => 'Sunday Service', 'speaker' => null, 'video_url' => 'https://vimeo.com/123'])), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertSee(route('sermons.watch', 11), false)->assertDontSee('/native/sermon/11', false);
    }

    public function test_the_live_card_is_hidden_when_nothing_is_live(): void
    {
        Http::fake(['site.test/api/v1/home' => Http::response($this->homeFeed(null)), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Home::class)->assertDontSee('LIVE NOW');
    }

    public function test_sermons_without_an_uploaded_image_show_their_youtube_thumbnail_in_the_list(): void
    {
        Http::fake(['site.test/api/v1/sermons' => Http::response(['data' => [
            ['id' => 11, 'title' => 'Seek First', 'speaker' => 'Pastor Sam', 'preached_at' => '2026-10-11', 'duration' => '38:00', 'views' => 5, 'thumbnail_url' => 'https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', 'series' => null],
        ]]), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(Sermons::class)->assertSee('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg', false);
    }

    public function test_the_return_page_steps_the_web_view_back(): void
    {
        $this->get('/app/return')->assertOk()->assertSee('window.history.back()', false);
    }

    public function test_opening_a_sermon_adds_it_to_recently_played_and_the_heart_saves_it(): void
    {
        Http::fake(['site.test/api/v1/sermons/11' => Http::response(['data' => $this->sermon()]), 'site.test/*' => Http::response(['data' => []])]);

        $screen = Native::test(NativeSermonPlayer::class, ['id' => 11]);

        $this->assertSame([11], LibraryStore::recent());
        $this->assertFalse(LibraryStore::isFavourite(11));

        $screen->call('toggleFavourite')->assertSet('isFavourite', true);
        $this->assertTrue(LibraryStore::isFavourite(11));

        $screen->call('toggleFavourite')->assertSet('isFavourite', false);
    }
}
