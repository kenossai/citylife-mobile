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
            'course' => 'Bible School International',
            'notifications' => 'Sunday gathering',
            'bible-reading-plan' => 'Your readings',
            'small-groups' => 'Find your people',
            'share-app' => 'Good things are better shared.',
            'location' => '1 South Parade',
            'call-us' => 'Let’s talk.',
            'email-us' => 'Send us a message.',
            'app-settings' => 'Your preferences',
            'help-support' => 'How can we help?',
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

    public function test_reading_plan_tracks_only_valid_days(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Bible Reading Plan', 'page' => 'bible-reading-plan'])
            ->call('toggleReading', 1)
            ->assertSet('completedReadings', [1])
            ->call('toggleReading', 99)
            ->assertSet('completedReadings', [1]);
    }

    public function test_course_lessons_can_be_completed_after_starting_the_course(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Course', 'page' => 'course'])
            ->call('toggleLesson', 1)
            ->assertSet('completedLessons', [])
            ->call('startCourse')
            ->call('toggleLesson', 1)
            ->assertSet('completedLessons', [1]);
    }

    public function test_app_preferences_can_be_changed_and_saved_to_the_session(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'App Settings', 'page' => 'app-settings'])
            ->call('toggleSetting', 'push_notifications')
            ->assertSet('settings.push_notifications', false);

        $this->assertSame(false, session('citylife.settings.push_notifications'));
    }

    public function test_help_answers_can_be_expanded_and_collapsed(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Help Support', 'page' => 'help-support'])
            ->call('toggleFaq', 'service')
            ->assertSet('openFaq', 'service')
            ->call('toggleFaq', 'service')
            ->assertSet('openFaq', null)
            ->call('toggleFaq', 'unknown')
            ->assertSet('openFaq', null);
    }

    public function test_group_interest_can_be_toggled(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'Small Groups', 'page' => 'small-groups'])
            ->call('toggleGroup', 'bible-study')
            ->assertSet('interestedGroups', ['bible-study'])
            ->call('toggleGroup', 'bible-study')
            ->assertSet('interestedGroups', []);
    }

    public function test_contact_pages_link_to_the_church_phone_and_email(): void
    {
        $this->get(route('call-us'))
            ->assertSee('tel:01142728243', false)
            ->assertSee('0114 272 8243');

        $this->get(route('email-us'))
            ->assertSee('mailto:admin1@citylifecc.com', false)
            ->assertSee('admin1@citylifecc.com');
    }
}
