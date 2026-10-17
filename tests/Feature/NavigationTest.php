<?php

namespace Tests\Feature;

use App\Livewire\More;
use Livewire\Livewire;
use Tests\TestCase;

class NavigationTest extends TestCase
{
    public function test_every_more_menu_item_links_to_an_existing_page(): void
    {
        $component = Livewire::test(More::class);

        foreach (collect($component->get('sections'))->flatten(1) as [$label, , , $route]) {
            $component->assertSee(route($route), false);
            $this->get(route($route))->assertOk();
        }
    }

    public function test_home_bell_links_to_notifications(): void
    {
        $this->get(route('home'))->assertSee(route('notifications'), false);
    }

    public function test_home_does_not_duplicate_bottom_navigation_shortcuts(): void
    {
        $this->get(route('home'))
            ->assertDontSee('class="card quick"')
            ->assertSee(route('events'), false)
            ->assertSee(route('sermons'), false)
            ->assertSee(route('give'), false);
    }

    public function test_back_links_are_marked_for_history_aware_navigation(): void
    {
        $this->get(route('events.show', 1))->assertSee('data-app-back', false);
        $this->get(route('sermons.series', 'faith-journey'))->assertSee('data-app-back', false);
        $this->get(route('course'))->assertSee('data-app-back', false);
        $this->get(route('prayer-requests'))->assertSee('data-app-back', false);
    }

    public function test_more_menu_links_preserve_the_menu_as_a_back_destination(): void
    {
        Livewire::test(More::class)
            ->assertSee('data-app-more-navigation', false);
    }

    public function test_home_shows_verse_shortcuts_and_latest_series(): void
    {
        $this->get(route('home'))
            ->assertSee('Verse of the Day')
            ->assertSee(route('prayer-requests'), false)
            ->assertSee(route('small-groups'), false)
            ->assertSee('Faith Journey');
    }
}
