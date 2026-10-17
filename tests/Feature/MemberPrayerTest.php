<?php

namespace Tests\Feature;

use App\Livewire\Login;
use App\Livewire\More;
use App\Livewire\PrayerRequests;
use App\Support\MemberSession;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class MemberPrayerTest extends TestCase
{
    private const MEMBER = [
        'id' => 1, 'first_name' => 'Ada', 'last_name' => 'Obi', 'name' => 'Ada Obi',
        'email' => 'ada@example.com', 'phone' => null, 'membership_number' => 'CL20261001', 'membership_status' => 'member', 'member_since' => '2024-03-15',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);
    }

    private function signIn(): void
    {
        session()->put(['citylife.member_token' => 'tok', 'citylife.member' => self::MEMBER]);
    }

    public function test_member_can_sign_in(): void
    {
        Http::fake(['site.test/api/v1/auth/login' => Http::response(['data' => ['token' => 'tok', 'member' => self::MEMBER]])]);

        Livewire::test(Login::class)
            ->set('email', 'ada@example.com')
            ->set('password', 'secret')
            ->call('signIn')
            ->assertHasNoErrors()
            ->assertRedirect(route('home'));

        $this->assertTrue(MemberSession::check());
        $this->assertSame('Ada Obi', MemberSession::member()['name']);
    }

    public function test_wrong_credentials_show_the_websites_message(): void
    {
        Http::fake(['site.test/api/v1/auth/login' => Http::response(['message' => 'These credentials do not match our records.'], 422)]);

        Livewire::test(Login::class)
            ->set('email', 'ada@example.com')
            ->set('password', 'nope')
            ->call('signIn')
            ->assertSet('error', 'These credentials do not match our records.')
            ->assertSet('password', '');

        $this->assertFalse(MemberSession::check());
    }

    public function test_sign_out_forgets_the_member(): void
    {
        Http::fake(['site.test/api/v1/auth/logout' => Http::response(['data' => null])]);
        $this->signIn();

        Livewire::test(More::class)->assertSee('Ada Obi')->call('signOut')->assertRedirect(route('home'));

        $this->assertFalse(MemberSession::check());
        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer tok'));
    }

    public function test_guests_see_a_sign_in_prompt_in_the_more_menu(): void
    {
        Livewire::test(More::class)->assertSee('Guest')->assertSee('Sign In');
    }

    public function test_profile_redirects_guests_to_sign_in_and_shows_member_details(): void
    {
        $this->get(route('profile'))->assertRedirect(route('login'));

        $this->signIn();

        $this->get(route('profile'))->assertOk()->assertSee('Ada Obi')->assertSee('CL20261001')->assertSee('ada@example.com');
    }

    public function test_browse_lists_approved_requests_from_the_website(): void
    {
        Http::fake(['site.test/api/v1/prayer-requests' => Http::response(['data' => [
            ['id' => 5, 'name' => 'Sarah', 'category' => 'Health', 'text' => 'Pray for my dad', 'prayers' => 3, 'is_private' => false, 'status' => 'approved', 'created_at' => '2026-10-10T09:00:00+00:00'],
        ]])]);

        Livewire::test(PrayerRequests::class)->assertSee('Pray for my dad')->assertSee('3 prayers')->assertDontSee('Sarah Johnson');
    }

    public function test_guest_submission_is_sent_to_the_website_and_kept_on_the_device(): void
    {
        Http::fake([
            'site.test/api/v1/prayer-requests' => Http::sequence()
                ->push(['data' => []])
                ->push(['data' => ['id' => 9, 'name' => 'Anonymous', 'category' => 'Family', 'text' => 'Pray for our family.', 'prayers' => 0, 'is_private' => false, 'status' => 'pending', 'created_at' => '2026-10-17T10:00:00+00:00']], 201),
        ]);

        Livewire::test(PrayerRequests::class)
            ->set('category', 'Family')
            ->set('request', 'Pray for our family.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('tab', 'mine')
            ->assertSee('Awaiting review')
            ->assertSee('Pray for our family.')
            ->assertSee('will appear in the app once our team has reviewed it');

        Http::assertSent(fn ($request) => $request->method() === 'POST' && $request['request'] === 'Pray for our family.' && ! $request->hasHeader('Authorization'));
    }

    public function test_member_submission_sends_their_token_and_loads_their_requests(): void
    {
        $this->signIn();

        Http::fake([
            'site.test/api/v1/prayer-requests/mine' => Http::response(['data' => [
                ['id' => 9, 'name' => 'Ada', 'category' => 'Health', 'text' => 'Private matter here', 'prayers' => 0, 'is_private' => true, 'status' => 'pending', 'created_at' => '2026-10-17T10:00:00+00:00'],
            ]]),
            'site.test/api/v1/prayer-requests' => Http::response(['data' => []], 200),
        ]);

        Livewire::test(PrayerRequests::class)
            ->assertSet('name', 'Ada')
            ->set('request', 'Private matter here')
            ->set('isPrivate', true)
            ->call('submit')
            ->assertSet('tab', 'mine')
            ->assertSee('Private matter here')
            ->assertSee('Private');

        Http::assertSent(fn ($request) => $request->method() === 'POST' && $request->hasHeader('Authorization', 'Bearer tok') && $request['is_private'] === true);
    }

    public function test_a_failed_submission_shows_an_error_and_keeps_the_text(): void
    {
        Http::fake(['site.test/api/v1/prayer-requests' => Http::sequence()->push(['data' => []])->pushStatus(500)]);

        Livewire::test(PrayerRequests::class)
            ->call('showTab', 'submit')
            ->set('request', 'Please keep praying for us.')
            ->call('submit')
            ->assertSet('tab', 'submit')
            ->assertSet('request', 'Please keep praying for us.')
            ->assertSee('Something went wrong');
    }

    public function test_praying_is_sent_once_per_request(): void
    {
        Http::fake([
            'site.test/api/v1/prayer-requests/5/pray' => Http::response(['data' => ['prayers' => 4]]),
            'site.test/api/v1/prayer-requests' => Http::response(['data' => [
                ['id' => 5, 'name' => 'Sarah', 'category' => 'Health', 'text' => 'Pray for my dad', 'prayers' => 3, 'is_private' => false, 'status' => 'approved', 'created_at' => '2026-10-10T09:00:00+00:00'],
            ]]),
        ]);

        Livewire::test(PrayerRequests::class)->call('pray', 5)->call('pray', 5)->assertSee('4 prayers');

        Http::assertSentCount(2);
    }

    public function test_guest_request_shows_as_approved_once_it_appears_in_the_public_list(): void
    {
        session()->put('citylife.my_prayer_requests', [
            ['id' => 5, 'name' => 'Anonymous', 'date' => 'Oct 17, 2026', 'category' => 'Health', 'text' => 'Pray for my dad', 'prayers' => 0, 'prayed' => false, 'status' => 'pending', 'private' => false],
        ]);

        Http::fake(['site.test/api/v1/prayer-requests' => Http::response(['data' => [
            ['id' => 5, 'name' => 'Anonymous', 'category' => 'Health', 'text' => 'Pray for my dad', 'prayers' => 2, 'is_private' => false, 'status' => 'approved', 'created_at' => '2026-10-17T09:00:00+00:00'],
        ]])]);

        Livewire::test(PrayerRequests::class)
            ->call('showTab', 'mine')
            ->assertDontSee('Awaiting review')
            ->assertSee('2 prayers');
    }

    public function test_a_visitor_account_is_labelled_by_status_not_as_a_member(): void
    {
        session()->put(['citylife.member_token' => 'tok', 'citylife.member' => [...self::MEMBER, 'membership_status' => 'visitor', 'member_since' => null]]);

        Livewire::test(More::class)->assertSee('Visitor')->assertDontSee('Member since');

        $this->get(route('profile'))->assertOk()->assertSee('Visitor')->assertDontSee('Member Since');
    }

    public function test_a_graduated_member_shows_when_they_became_a_member(): void
    {
        $this->signIn();

        Livewire::test(More::class)->assertSee('Member since 2024');

        $this->get(route('profile'))->assertSee('Member Since')->assertSee('March 15, 2024');
    }

    public function test_the_login_screen_points_to_the_website_for_passwords(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Forgot your password?')->assertSee('enrol on a course');
    }

    public function test_an_expired_token_signs_the_member_out(): void
    {
        $this->signIn();
        Http::fake(['site.test/api/v1/prayer-requests/mine' => Http::response(['message' => 'Unauthenticated.'], 401), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(PrayerRequests::class)->assertSet('signedIn', false);

        $this->assertFalse(MemberSession::check());
    }

    public function test_profile_has_no_dead_buttons_when_connected_to_the_website(): void
    {
        $this->signIn();

        $this->get(route('profile'))->assertSee('Change Password')->assertDontSee('Delete Account')->assertDontSee('Privacy Settings');
    }
}
