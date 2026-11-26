<?php

namespace Tests\Feature;

use App\Livewire\Placeholder;
use App\Support\Theme;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class ThemeTest extends TestCase
{
    public function test_the_app_follows_the_device_until_a_choice_is_made(): void
    {
        $this->assertSame('system', Theme::preference());

        $this->get(route('home'))->assertSee('data-theme="system" data-resolved="light"', false);
    }

    public function test_a_device_in_dark_mode_is_drawn_dark_straight_away_using_the_cookie_the_page_script_leaves(): void
    {
        $this->withUnencryptedCookie(Theme::COOKIE, 'dark')
            ->get(route('home'))
            ->assertSee('data-theme="system" data-resolved="dark"', false);
    }

    public function test_the_resolved_cookie_is_readable_by_the_server_and_ignores_junk(): void
    {
        $this->withUnencryptedCookie(Theme::COOKIE, 'purple')->get(route('home'))->assertSee('data-resolved="light"', false);
    }

    public function test_choosing_dark_or_light_overrides_the_device_setting(): void
    {
        session()->put('citylife.theme', 'dark');
        $this->withUnencryptedCookie(Theme::COOKIE, 'light')->get(route('home'))->assertSee('data-theme="dark" data-resolved="dark"', false);

        session()->put('citylife.theme', 'light');
        $this->withUnencryptedCookie(Theme::COOKIE, 'dark')->get(route('home'))->assertSee('data-theme="light" data-resolved="light"', false);
    }

    public function test_an_unknown_stored_theme_falls_back_to_the_device_setting(): void
    {
        session()->put('citylife.theme', 'sepia');

        $this->assertSame('system', Theme::preference());
        $this->assertFalse(Theme::set('sepia'));
        $this->assertSame('system', Theme::preference());
    }

    public function test_the_choice_is_made_in_app_settings_and_remembered(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'App Settings', 'page' => 'app-settings'])
            ->assertSet('theme', 'system')
            ->assertSee('Appearance')
            ->call('setTheme', 'dark')
            ->assertSet('theme', 'dark')
            ->assertDispatched('theme-changed', theme: 'dark')
            ->assertSee('Always dark');

        $this->assertSame('dark', session('citylife.theme'));
        $this->get(route('app-settings'))->assertSee('data-theme="dark"', false);
    }

    public function test_an_invalid_theme_choice_is_ignored(): void
    {
        Livewire::test(Placeholder::class, ['title' => 'App Settings', 'page' => 'app-settings'])
            ->call('setTheme', 'neon')
            ->assertSet('theme', 'system')
            ->assertNotDispatched('theme-changed');

        $this->assertNull(session('citylife.theme'));
    }

    public function test_the_dark_styles_are_part_of_every_page(): void
    {
        $this->get(route('give'))->assertSee('html[data-resolved="dark"] .card', false)->assertSee('color-scheme" content="light dark"', false);
    }

    public function test_every_dark_rule_stays_inside_the_dark_scope_so_light_mode_is_untouched(): void
    {
        $css = file_get_contents(resource_path('views/components/layouts/dark-theme.blade.php'));
        $css = preg_replace('~\{\{--.*?--\}\}~s', '', $css);
        preg_match_all('~(?:^|\})\s*([^{}@]+)\{~m', $css, $selectors);

        $this->assertNotEmpty($selectors[1]);

        foreach ($selectors[1] as $group) {
            foreach (explode(',', $group) as $selector) {
                $this->assertStringStartsWith('html[data-resolved="dark"]', trim($selector), "Unscoped dark rule: {$selector}");
            }
        }
    }

    public function test_the_bible_reader_tells_youversions_styles_which_theme_to_use(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        Cache::flush();
        Http::fake([
            'site.test/api/v1/bible/versions' => Http::response(['data' => [['id' => 3034, 'abbreviation' => 'BSB', 'title' => 'Berean Standard Bible', 'language' => 'en']]]),
            'site.test/api/v1/bible/3034/books' => Http::response(['data' => [['id' => 'JHN', 'title' => 'John', 'abbreviation' => 'John', 'canon' => 'new_testament', 'chapters' => 21]]]),
            'site.test/api/v1/bible/3034/books/JHN/chapters/3' => Http::response(['data' => ['reference' => 'John 3', 'html' => '<p>Text</p>', 'copyright' => 'Public Domain', 'version' => ['id' => 3034, 'abbreviation' => 'BSB', 'title' => 'x'], 'previous' => null, 'next' => null]]),
        ]);

        session()->put('citylife.theme', 'dark');

        $this->get(route('bible.read', ['JHN', 3]))->assertSee('data-yv-theme="dark"', false);
    }
}
