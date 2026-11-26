<?php

namespace Tests\Feature;

use App\Livewire\ReadingPassage;
use App\Livewire\ReadingPlan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ReadingPassageTest extends TestCase
{
    private const PLAN = [
        'id' => 7, 'title' => 'The Gospel of John in 21 days', 'description' => null,
        'days' => [
            ['day' => 1, 'title' => 'The Word became flesh', 'reference' => 'John 1'],
            ['day' => 2, 'title' => 'Water into wine', 'reference' => 'John 2'],
            ['day' => 3, 'title' => 'Born again', 'reference' => 'John 3'],
        ],
    ];

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        config(['services.citylife.url' => 'https://site.test']);
    }

    /** @return array<string, mixed> */
    private function passages(array $overrides = []): array
    {
        return [
            'translation' => ['name' => 'World English Bible', 'note' => 'Public Domain'],
            'passages' => [['reference' => 'John 2', 'verses' => [
                ['chapter' => 2, 'verse' => 1, 'text' => 'The third day, there was a wedding in Cana of Galilee.'],
                ['chapter' => 2, 'verse' => 2, 'text' => 'Jesus also was invited, and his disciples, to the wedding.'],
            ]]],
            'unavailable' => [],
            ...$overrides,
        ];
    }

    private function website(mixed $passage): void
    {
        Http::fake([
            'site.test/api/v1/reading-plan' => Http::response(['data' => self::PLAN]),
            'site.test/api/v1/passages*' => $passage,
        ]);
    }

    public function test_a_day_shows_the_scripture_text_with_verse_numbers_and_credit(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        $this->get(route('bible-reading-plan.day', 2))
            ->assertOk()
            ->assertSee('Water into wine')
            ->assertSee('DAY 2 OF 3')
            ->assertSee('there was a wedding in Cana of Galilee.')
            ->assertSee('<sup>2</sup>', false)
            ->assertSee('World English Bible')
            ->assertSee('Public Domain');

        Http::assertSent(fn ($request) => str_contains($request->url(), 'passages?reference=John%202'));
    }

    public function test_the_plan_screen_links_each_day_to_its_reading(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        Livewire::test(ReadingPlan::class)->assertSee(route('bible-reading-plan.day', 3), false);
    }

    public function test_the_built_in_demo_plan_has_no_links_to_missing_text(): void
    {
        config(['services.citylife.url' => null]);

        Livewire::test(ReadingPlan::class)->assertDontSee('/bible-reading-plan/1', false);
    }

    public function test_a_day_can_be_marked_done_and_it_shows_on_the_plan(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        Livewire::test(ReadingPassage::class, ['day' => 2])
            ->assertSet('completed', false)
            ->call('toggleDone')
            ->assertSet('completed', true)
            ->assertSee('Completed ✓')
            ->call('toggleDone')
            ->assertSet('completed', false);

        Livewire::test(ReadingPassage::class, ['day' => 2])->call('toggleDone');
        $this->assertSame([2], session('citylife.reading_progress.7'));

        Livewire::test(ReadingPlan::class)->assertSet('completed', [2]);
    }

    public function test_previous_and_next_days_link_around_the_current_one(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        $this->get(route('bible-reading-plan.day', 2))->assertSee(route('bible-reading-plan.day', 1), false)->assertSee(route('bible-reading-plan.day', 3), false);
        $this->get(route('bible-reading-plan.day', 1))->assertDontSee('← Day', false)->assertSee('Day 2 →', false);
        $this->get(route('bible-reading-plan.day', 3))->assertSee('← Day 2', false)->assertDontSee('Day 4');
    }

    public function test_a_day_that_is_not_in_the_plan_is_not_found(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        $this->get(route('bible-reading-plan.day', 99))->assertNotFound();
    }

    public function test_a_day_is_not_found_when_no_plan_is_live(): void
    {
        Http::fake(['site.test/api/v1/reading-plan' => Http::response(['data' => null])]);

        $this->get(route('bible-reading-plan.day', 1))->assertNotFound();
    }

    public function test_part_of_a_reading_being_unavailable_is_explained_with_a_retry(): void
    {
        $this->website(Http::response(['data' => $this->passages(['unavailable' => ['Psalm 1']])]));

        Livewire::test(ReadingPassage::class, ['day' => 2])
            ->assertSee('there was a wedding')
            ->assertSee("Part of this reading couldn't be loaded", false)
            ->assertSee('Psalm 1')
            ->assertSee('Try again');
    }

    public function test_when_no_text_comes_back_the_reference_is_shown_with_a_retry(): void
    {
        $this->website(Http::response(['data' => $this->passages(['passages' => [], 'unavailable' => ['John 2']])]));

        Livewire::test(ReadingPassage::class, ['day' => 2])
            ->assertSee('The scripture text is busy right now')
            ->assertSee('Read John 2 in your Bible')
            ->assertSee('Try again');
    }

    public function test_when_the_website_is_down_the_reference_is_still_shown(): void
    {
        Http::fake([
            'site.test/api/v1/reading-plan' => Http::response(['data' => self::PLAN]),
            'site.test/api/v1/passages*' => Http::response([], 500),
        ]);

        Livewire::test(ReadingPassage::class, ['day' => 2])
            ->assertSee("We can't load the scripture text right now", false)
            ->assertSee('Read John 2 in your Bible')
            ->assertSee('Water into wine');
    }

    public function test_retrying_asks_for_the_text_again(): void
    {
        Http::fake([
            'site.test/api/v1/reading-plan' => Http::response(['data' => self::PLAN]),
            'site.test/api/v1/passages*' => Http::sequence()->push(['data' => $this->passages(['passages' => [], 'unavailable' => ['John 2']])])->push(['data' => $this->passages()]),
        ]);

        Livewire::test(ReadingPassage::class, ['day' => 2])
            ->assertSee('busy right now')
            ->call('retry')
            ->assertSee('there was a wedding in Cana of Galilee.')
            ->assertDontSee('busy right now');
    }

    public function test_passage_text_is_kept_for_a_day_so_it_is_not_fetched_on_every_visit(): void
    {
        $this->website(Http::response(['data' => $this->passages()]));

        $this->get(route('bible-reading-plan.day', 2));
        $this->get(route('bible-reading-plan.day', 2));

        $passageRequests = Http::recorded(fn ($request) => str_contains($request->url(), '/passages'))->count();

        $this->assertSame(1, $passageRequests);
    }
}
