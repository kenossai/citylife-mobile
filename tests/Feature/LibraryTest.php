<?php

namespace Tests\Feature;

use App\Livewire\BookDetail;
use App\Livewire\Home;
use App\Livewire\Library;
use App\Livewire\More;
use App\Support\LibraryStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class LibraryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);
        Http::fake([
            'site.test/api/v1/sermons' => Http::response(['data' => [
                $this->sermon(1, 'Walking in Faith'),
                $this->sermon(2, 'Grace Greater'),
            ]]),
            'site.test/api/v1/books/abide' => Http::response(['data' => $this->book()]),
            'site.test/api/v1/books' => Http::response(['data' => [$this->book()]]),
            'site.test/api/v1/series' => Http::response(['data' => [
                ['slug' => 'faith-journey', 'title' => 'Faith Journey', 'description' => 'Six weeks', 'color' => '#374151', 'sermons' => []],
            ]]),
        ]);
    }

    /** @return array<string, mixed> */
    private function sermon(int $id, string $title): array
    {
        return ['id' => $id, 'title' => $title, 'speaker' => 'Pastor Sam', 'preached_at' => '2026-10-04', 'duration' => '45:00', 'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'series' => null, 'thumbnail_url' => null];
    }

    public function test_the_bottom_bar_has_bible_instead_of_events(): void
    {
        $this->get(route('home'))->assertOk()
            ->assertSee('href="'.route('bible').'"', false)
            ->assertDontSee('<span>Events</span>', false);
    }

    public function test_the_home_shortcut_is_library_and_the_more_menu_has_no_bible_entry(): void
    {
        Livewire::test(Home::class)->assertSee('Library')->assertSee(route('library'), false);

        Livewire::test(More::class)->assertDontSee('href="'.route('bible').'"', false)->assertSee(route('bible-reading-plan'), false);
    }

    public function test_recently_played_lists_the_latest_first_without_repeats(): void
    {
        LibraryStore::played(1);
        LibraryStore::played(2);
        LibraryStore::played(1);

        $this->assertSame([1, 2], LibraryStore::recent());

        Livewire::test(Library::class)->assertSeeInOrder(['Recently Played', 'Walking in Faith', 'Grace Greater']);
    }

    public function test_recently_played_explains_itself_when_empty(): void
    {
        Livewire::test(Library::class)->assertSee('Sermons you watch will show up here.')->assertSee('Shuffle Play');
    }

    public function test_favourites_can_be_saved_listed_and_removed(): void
    {
        $this->assertTrue(LibraryStore::toggleFavourite(2));
        $this->assertTrue(LibraryStore::isFavourite(2));

        Livewire::test(Library::class)->call('showTab', 'favourites')->assertSee('Grace Greater')->assertDontSee('Walking in Faith');

        $this->assertFalse(LibraryStore::toggleFavourite(2));

        Livewire::test(Library::class)->call('showTab', 'favourites')->assertSee('Tap the heart');
    }

    public function test_playlists_are_the_sermon_series(): void
    {
        Livewire::test(Library::class)->call('showTab', 'playlists')->assertSee('Faith Journey')->assertSee(route('sermons.series', 'faith-journey'), false);
    }

    public function test_an_unknown_tab_is_ignored(): void
    {
        Livewire::test(Library::class)->call('showTab', 'nonsense')->assertSet('tab', 'sermons');
    }

    public function test_shuffle_opens_one_of_the_sermons(): void
    {
        Livewire::test(Library::class)->call('shuffle')->assertRedirect();
    }

    public function test_the_library_says_so_when_the_website_cannot_be_reached(): void
    {
        config(['services.citylife.url' => 'https://down.test']);
        Http::fake(['down.test/*' => Http::response([], 500)]);

        Livewire::test(Library::class)->assertSee('be loaded right now');
    }

    /** @return array<string, mixed> */
    private function book(): array
    {
        return ['id' => 1, 'slug' => 'abide', 'title' => 'Abide', 'subtitle' => null, 'author' => 'Jim Master', 'description' => 'Stay close.', 'cover_url' => 'https://img.test/abide.jpg', 'categories' => ['Devotional'], 'page_count' => 120, 'publisher' => null, 'published_month' => null, 'format' => 'Paperback', 'language' => 'en', 'amazon_url' => 'https://amazon.test/abide', 'kindle_url' => null];
    }

    public function test_the_books_tab_lists_the_websites_books_and_links_to_each(): void
    {
        Livewire::test(Library::class)->call('showTab', 'books')->assertSee('Abide')->assertSee('Jim Master')->assertSee('Devotional')->assertSee(route('library.book', 'abide'), false);
    }

    public function test_a_book_page_shows_its_details_and_opens_the_store_in_the_app_browser(): void
    {
        Livewire::test(BookDetail::class, ['slug' => 'abide'])
            ->assertSee('Abide')
            ->assertSee('Stay close.')
            ->assertSee('120 pages')
            ->assertSee('Get it on Amazon')
            ->assertDontSee('Read on Kindle')
            ->call('openAmazon')
            ->assertDispatched('open-external', url: 'https://amazon.test/abide');
    }

    public function test_an_unknown_book_is_not_found(): void
    {
        Http::fake(['site.test/api/v1/books/nope' => Http::response([], 404)]);

        $this->get(route('library.book', 'nope'))->assertNotFound();
    }
}
