<?php

namespace Tests\Feature;

use App\Livewire\Profile;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    public function test_profile_page_shows_member_details(): void
    {
        $this->get(route('profile'))
            ->assertOk()
            ->assertSee('Contact Information')
            ->assertSee('CM2023-0152')
            ->assertSee('Delete Account');
    }

    public function test_preference_can_be_toggled(): void
    {
        Livewire::test(Profile::class)
            ->assertSet('preferences.eventReminders', false)
            ->call('togglePreference', 'eventReminders')
            ->assertSet('preferences.eventReminders', true);
    }

    public function test_unknown_preference_is_ignored(): void
    {
        Livewire::test(Profile::class)
            ->call('togglePreference', 'unknown')
            ->assertSet('preferences', [
                'pushNotifications' => true,
                'emailNewsletters' => true,
                'eventReminders' => false,
            ]);
    }
}
