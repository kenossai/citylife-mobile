<?php

namespace Tests\Feature;

use App\Livewire\Placeholder;
use Livewire\Livewire;
use Tests\TestCase;

class PlaceholderTest extends TestCase
{
    public function test_every_additional_app_page_renders_its_own_content(): void
    {
        $pages = [
            'notifications' => 'Sunday gathering',
            'app-settings' => 'Your preferences',
        ];

        foreach ($pages as $route => $content) {
            $this->get(route($route))
                ->assertOk()
                ->assertSee($content)
                ->assertDontSee('Coming soon.');
        }
    }

    public function test_notifications_can_be_marked_read_and_saved_to_the_session(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Notifications', 'page' => 'notifications'])
            ->call('markRead', 'sunday-service')
            ->assertSet('notifications.0.read', true);

        $this->assertSame(['sunday-service'], session('citylife.read_notifications'));
    }

    public function test_marking_all_notifications_as_read_clears_the_home_badge(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Notifications', 'page' => 'notifications'])
            ->call('markAllRead')
            ->assertSet('notifications.0.read', true)
            ->assertSet('notifications.1.read', true);

        $this->get(route('home'))->assertDontSee('<i>2</i>', false)->assertDontSee('<i>1</i>', false);
    }

    public function test_app_preferences_can_be_changed_and_saved_to_the_session(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'App Settings', 'page' => 'app-settings'])
            ->call('toggleSetting', 'push_notifications')
            ->assertSet('settings.push_notifications', false);

        $this->assertSame(false, session('citylife.settings.push_notifications'));
    }
}
