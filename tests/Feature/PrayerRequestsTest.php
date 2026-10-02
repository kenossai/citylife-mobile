<?php

namespace Tests\Feature;

use App\Livewire\PrayerRequests;
use Livewire\Livewire;
use Tests\TestCase;

class PrayerRequestsTest extends TestCase
{
    public function test_page_shows_the_submit_form_and_guidelines(): void
    {
        Livewire::test(PrayerRequests::class)
            ->call('showTab', 'submit')
            ->assertSee('Submit a Prayer Request')
            ->assertSee('Guidelines');
    }

    public function test_browse_tab_lists_prayer_requests_by_default(): void
    {
        $this->get(route('prayer-requests'))
            ->assertOk()
            ->assertSee('Sarah Johnson')
            ->assertSee('Jan 9, 2026')
            ->assertSee('45 prayers');
    }

    public function test_request_text_is_required(): void
    {
        Livewire::test(PrayerRequests::class)
            ->call('submit')
            ->assertHasErrors(['request' => 'required']);
    }

    public function test_category_must_be_listed(): void
    {
        Livewire::test(PrayerRequests::class)
            ->set('category', 'Unknown')
            ->set('request', 'Please pray for our family.')
            ->call('submit')
            ->assertHasErrors(['category' => 'in']);
    }

    public function test_blank_name_is_submitted_anonymously(): void
    {
        Livewire::test(PrayerRequests::class)
            ->set('request', 'Please pray for our family.')
            ->call('submit')
            ->assertHasNoErrors()
            ->assertSet('tab', 'mine')
            ->assertSet('requests.0.name', 'Anonymous')
            ->assertSet('requests.0.text', 'Please pray for our family.')
            ->assertSet('request', '');
    }

    public function test_praying_for_a_request_counts_once(): void
    {
        Livewire::test(PrayerRequests::class)
            ->call('pray', 1)
            ->call('pray', 1)
            ->assertSet('requests.0.prayers', 46);
    }
}
