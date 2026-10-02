<?php

namespace Tests\Feature;

use App\Livewire\Give;
use Livewire\Livewire;
use Tests\TestCase;

class GiveTest extends TestCase
{
    public function test_give_page_renders_amounts_and_funds(): void
    {
        $this->get(route('give'))
            ->assertOk()
            ->assertSee('£25')
            ->assertSee('Tithes')
            ->assertSee('Give £25.00');
    }

    public function test_choosing_a_preset_clears_the_custom_amount(): void
    {
        Livewire::test(Give::class)
            ->set('custom', '30')
            ->assertSet('preset', null)
            ->call('choose', 100)
            ->assertSet('preset', 100)
            ->assertSet('custom', '');
    }

    public function test_unknown_preset_is_ignored(): void
    {
        Livewire::test(Give::class)->call('choose', 7)->assertSet('preset', 25);
    }

    public function test_custom_amount_is_used_for_the_gift(): void
    {
        Livewire::test(Give::class)
            ->set('custom', '42.50')
            ->set('fund', 'Missions')
            ->set('frequency', 'monthly')
            ->call('give')
            ->assertHasNoErrors()
            ->assertSet('given', true)
            ->assertSee('£42.50')
            ->assertSee('Missions');
    }

    public function test_invalid_amount_is_rejected(): void
    {
        Livewire::test(Give::class)
            ->set('custom', '0')
            ->call('give')
            ->assertHasErrors(['custom'])
            ->assertSet('given', false);
    }

    public function test_invalid_fund_is_rejected(): void
    {
        Livewire::test(Give::class)
            ->set('fund', 'Nope')
            ->call('give')
            ->assertHasErrors(['fund']);
    }
}
