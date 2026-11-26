<?php

namespace Tests\Feature;

use App\Livewire\BibleBooks;
use App\Livewire\BibleReader;
use App\Livewire\More;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class BibleTest extends TestCase
{
    private const VERSIONS = [
        ['id' => 3034, 'abbreviation' => 'BSB', 'title' => 'Berean Standard Bible', 'language' => 'en', 'copyright' => 'Berean Standard Bible. Public domain.'],
        ['id' => 111, 'abbreviation' => 'NIV', 'title' => 'New International Version', 'language' => 'en', 'copyright' => 'NIV copyright text.'],
    ];

    private const BOOKS = [
        ['id' => 'GEN', 'title' => 'Genesis', 'abbreviation' => 'Gen.', 'canon' => 'old_testament', 'chapters' => 50],
        ['id' => 'JHN', 'title' => 'John', 'abbreviation' => 'John', 'canon' => 'new_testament', 'chapters' => 21],
        ['id' => 'ACT', 'title' => 'Acts', 'abbreviation' => 'Acts', 'canon' => 'new_testament', 'chapters' => 28],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);
    }

    /** @return array<string, mixed> */
    private function chapter(string $reference = 'John 3', array $overrides = []): array
    {
        return [
            'reference' => $reference,
            'html' => '<div class="p"><span class="verse" data-usfm="JHN.3.16"><span class="label">16</span>For God so loved the world.</span></div>',
            'copyright' => 'Berean Standard Bible. Public domain.',
            'version' => ['id' => 3034, 'abbreviation' => 'BSB', 'title' => 'Berean Standard Bible'],
            'previous' => ['book' => 'JHN', 'chapter' => 2],
            'next' => ['book' => 'JHN', 'chapter' => 4],
            ...$overrides,
        ];
    }

    private function website(array $extra = []): void
    {
        Http::fake($extra + [
            'site.test/api/v1/bible/versions' => Http::response(['data' => self::VERSIONS]),
            'site.test/api/v1/bible/3034/books' => Http::response(['data' => self::BOOKS]),
            'site.test/api/v1/bible/111/books' => Http::response(['data' => self::BOOKS]),
            'site.test/api/v1/bible/*/books/JHN/chapters/3' => Http::response(['data' => $this->chapter()]),
            'site.test/*' => Http::response(['data' => []]),
        ]);
    }

    public function test_the_bible_home_lists_books_by_testament_and_names_the_version(): void
    {
        $this->website();

        Livewire::test(BibleBooks::class)
            ->assertSee('BSB')
            ->assertSee('John')
            ->assertSeeInOrder(['John', '21', 'chapters'])
            ->assertDontSee('Genesis')
            ->call('$set', 'testament', 'old_testament')
            ->assertSee('Genesis')
            ->assertDontSee('Acts');
    }

    public function test_searching_finds_a_book_in_either_testament(): void
    {
        $this->website();

        Livewire::test(BibleBooks::class)->set('search', 'gen')->assertSee('Genesis')->assertDontSee('Acts')->set('search', 'zzz')->assertSee('No books found');
    }

    public function test_the_version_can_be_switched_among_the_licensed_ones_and_is_remembered(): void
    {
        $this->website();

        Livewire::test(BibleBooks::class)
            ->assertSet('versionId', 3034)
            ->call('selectVersion', 111)
            ->assertSet('versionId', 111)
            ->call('selectVersion', 999)
            ->assertSet('versionId', 111);

        $this->assertSame(111, session('citylife.bible_version'));
        Livewire::test(BibleBooks::class)->assertSet('versionId', 111);
    }

    public function test_the_bible_says_so_when_it_is_not_set_up_on_the_website(): void
    {
        Http::fake(['site.test/api/v1/bible/versions' => Http::response(['data' => null]), 'site.test/*' => Http::response(['data' => []])]);

        Livewire::test(BibleBooks::class)->assertSet('unavailable', true)->assertSee("The Bible isn't available right now", false);
        $this->get(route('bible.read', ['JHN', 3]))->assertNotFound();
    }

    public function test_the_bible_is_unavailable_in_a_build_with_no_website(): void
    {
        config(['services.citylife.url' => null]);

        Livewire::test(BibleBooks::class)->assertSet('unavailable', true);
    }

    public function test_a_book_page_offers_every_chapter(): void
    {
        $this->website();

        $this->get(route('bible.book', 'JHN'))
            ->assertOk()
            ->assertSee('John')
            ->assertSee(route('bible.read', ['JHN', 1]), false)
            ->assertSee(route('bible.read', ['JHN', 21]), false)
            ->assertDontSee(route('bible.read', ['JHN', 22]), false);

        $this->get(route('bible.book', 'ZZZ'))->assertNotFound();
    }

    public function test_a_chapter_shows_the_text_the_stylesheets_and_the_copyright(): void
    {
        $this->website();

        $this->get(route('bible.read', ['JHN', 3]))
            ->assertOk()
            ->assertSee('John 3')
            ->assertSee('For God so loved the world.')
            ->assertSee('data-slot="yv-bible-renderer"', false)
            ->assertSee('https://cdn.youversion.com/platform/1/bible.css', false)
            ->assertSee('https://site.test/api/v1/bible/fonts.css', false)
            ->assertSee('Berean Standard Bible. Public domain.')
            ->assertSee(route('bible.read', ['JHN', 2]), false)
            ->assertSee(route('bible.read', ['JHN', 4]), false);
    }

    public function test_the_app_key_is_never_in_the_page(): void
    {
        $this->website();

        $this->assertStringNotContainsString('app_key', $this->get(route('bible.read', ['JHN', 3]))->getContent());
    }

    public function test_the_place_being_read_is_remembered_for_continue_reading(): void
    {
        $this->website();

        $this->get(route('bible.read', ['JHN', 3]));

        $this->assertSame(['book' => 'JHN', 'chapter' => 3], session('citylife.bible_last'));

        Livewire::test(BibleBooks::class)->assertSee('CONTINUE READING')->assertSee('John 3')->assertSet('testament', 'new_testament');
    }

    public function test_a_chapter_that_is_not_in_the_book_is_not_found(): void
    {
        $this->website();

        $this->get(route('bible.read', ['JHN', 22]))->assertNotFound();
        $this->get(route('bible.read', ['ZZZ', 1]))->assertNotFound();
    }

    public function test_text_size_can_be_changed_within_limits_and_is_remembered(): void
    {
        $this->website();

        Livewire::test(BibleReader::class, ['book' => 'JHN', 'chapter' => 3])
            ->assertSet('fontSize', 18)
            ->call('resize', 2)
            ->assertSet('fontSize', 20)
            ->assertSee('--yv-reader-font-size: 20px', false)
            ->call('resize', 20)
            ->assertSet('fontSize', 28)
            ->call('resize', -40)
            ->assertSet('fontSize', 15);

        $this->assertSame(15, session('citylife.bible_font'));
    }

    public function test_switching_version_while_reading_reloads_the_same_chapter(): void
    {
        $this->website(['site.test/api/v1/bible/111/books/JHN/chapters/3' => Http::response(['data' => $this->chapter('John 3', ['copyright' => 'NIV copyright text.', 'html' => '<p>NIV words of John three.</p>'])])]);

        Livewire::test(BibleReader::class, ['book' => 'JHN', 'chapter' => 3])
            ->assertSee('For God so loved the world.')
            ->call('selectVersion', 111)
            ->assertSee('NIV words of John three.')
            ->assertSee('NIV copyright text.')
            ->assertDontSee('For God so loved the world.');
    }

    public function test_switching_to_a_version_without_that_book_returns_to_the_book_list(): void
    {
        $this->website(['site.test/api/v1/bible/111/books' => Http::response(['data' => [self::BOOKS[0]]])]);

        Livewire::test(BibleReader::class, ['book' => 'JHN', 'chapter' => 3])->call('selectVersion', 111)->assertRedirect(route('bible'));
    }

    public function test_a_chapter_that_cannot_be_loaded_offers_a_retry(): void
    {
        $this->website([
            'site.test/api/v1/bible/*/books/JHN/chapters/3' => Http::sequence()->push(['data' => null])->push(['data' => $this->chapter()]),
        ]);

        Livewire::test(BibleReader::class, ['book' => 'JHN', 'chapter' => 3])
            ->assertSee("We can't load this chapter right now", false)
            ->assertSee('Try again')
            ->call('retry')
            ->assertSee('For God so loved the world.');
    }

    public function test_the_bible_is_reachable_from_the_home_screen_and_the_more_menu(): void
    {
        $this->get(route('home'))->assertSee(route('bible'), false);

        Livewire::test(More::class)->assertSee(route('bible'), false)->assertDontSee('/native/events', false)->assertDontSee('/native/prayer', false);
    }
}
