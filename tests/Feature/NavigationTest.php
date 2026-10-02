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
}
