<?php

namespace Tests\Feature;

use App\Livewire\Sermons;
use Livewire\Livewire;
use Tests\TestCase;

class SermonsTest extends TestCase
{
    public function test_recent_tab_lists_newest_sermon_first(): void
    {
        $this->get(route('sermons'))
            ->assertOk()
            ->assertSeeInOrder(['Walking in Faith', 'The Power of Prayer', 'Living with Purpose'])
            ->assertSee('Dec 29, 2025')
            ->assertSee('45 min');
    }

    public function test_search_filters_by_title(): void
    {
        Livewire::test(Sermons::class)
            ->set('search', 'prayer')
            ->assertSee('The Power of Prayer')
            ->assertDontSee('Walking in Faith');
    }

    public function test_search_without_matches_shows_empty_message(): void
    {
        Livewire::test(Sermons::class)
            ->set('search', 'zzz')
            ->assertSee('No sermons found.');
    }

    public function test_popular_tab_orders_by_views(): void
    {
        Livewire::test(Sermons::class)
            ->call('showTab', 'popular')
            ->assertSeeInOrder(['The Power of Prayer', 'Grace That Restores', 'Walking in Faith']);
    }

    public function test_unknown_tab_is_ignored(): void
    {
        Livewire::test(Sermons::class)
            ->call('showTab', 'unknown')
            ->assertSet('tab', 'recent');
    }

    public function test_series_tab_links_to_series_detail(): void
    {
        Livewire::test(Sermons::class)
            ->call('showTab', 'series')
            ->assertSee('Faith Journey')
            ->assertSee('6 sermons')
            ->assertSee(route('sermons.series', 'faith-journey'));
    }

    public function test_series_detail_lists_numbered_messages(): void
    {
        $this->get(route('sermons.series', 'faith-journey'))
            ->assertOk()
            ->assertSee('About This Series')
            ->assertSee('6 Messages')
            ->assertSee('~43 min each')
            ->assertSeeInOrder(['Introduction to Faith Journey', 'Building Strong Foundations']);
    }

    public function test_unknown_series_returns_not_found(): void
    {
        $this->get(route('sermons.series', 'nope'))->assertNotFound();
    }

    public function test_player_shows_message_and_neighbours(): void
    {
        $this->get(route('sermons.play', ['faith-journey', 2]))
            ->assertOk()
            ->assertSee('Building Strong Foundations')
            ->assertSee('Message 2')
            ->assertSee(route('sermons.play', ['faith-journey', 1]))
            ->assertSee(route('sermons.play', ['faith-journey', 3]));
    }

    public function test_player_returns_not_found_for_unknown_message(): void
    {
        $this->get(route('sermons.play', ['faith-journey', 99]))->assertNotFound();
    }

    public function test_series_messages_link_to_the_player(): void
    {
        $this->get(route('sermons.series', 'faith-journey'))
            ->assertSee(route('sermons.play', ['faith-journey', 1]));
    }
}
