<?php

namespace Tests\Feature;

use App\Livewire\Give;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class GiveTest extends TestCase
{
    private function fakeChurch(): void
    {
        config(['services.citylife.url' => 'https://site.test']);
        Cache::flush();

        Http::fake([
            'site.test/api/v1/church' => Http::response(['data' => [
                'giving' => [
                    'card_url' => 'https://pay.example/card',
                    'paypal_url' => 'https://pay.example/paypal',
                    'bank' => ['bank' => 'Virgin Money', 'account_name' => 'City Life', 'sort_code' => '05-08-48', 'account_number' => '20057965', 'reference' => 'Your Name + Fund'],
                    'in_person' => 'Giving envelopes are available at every service.',
                ],
            ]]),
            'site.test/api/v1/gift-aid' => Http::response(['data' => null], 201),
        ]);
    }

    public function test_give_shows_the_websites_giving_methods(): void
    {
        $this->fakeChurch();

        $this->get(route('give'))
            ->assertOk()
            ->assertSee('Give by card')
            ->assertSee('Give with PayPal')
            ->assertSee('05-08-48')
            ->assertSee('20057965')
            ->assertSee('Giving envelopes are available at every service.');
    }

    public function test_card_and_paypal_open_the_payment_pages(): void
    {
        $this->fakeChurch();

        Livewire::test(Give::class)->call('openCard')->assertRedirect('https://pay.example/card');
        Livewire::test(Give::class)->call('openPayPal')->assertRedirect('https://pay.example/paypal');
    }

    public function test_give_explains_when_nothing_can_be_loaded(): void
    {
        $this->get(route('give'))->assertOk()->assertSee('Giving details can');
    }

    public function test_gift_aid_requires_every_field_and_the_confirmation(): void
    {
        $this->fakeChurch();

        Livewire::test(Give::class)
            ->call('toggleGiftAid')
            ->set('declarationDate', '')
            ->call('submitGiftAid')
            ->assertHasErrors(['firstName', 'lastName', 'address', 'postcode', 'phone', 'email', 'giftAidCode', 'declarationDate', 'confirmed']);

        Http::assertNotSent(fn ($request) => str_ends_with($request->url(), '/gift-aid'));
    }

    public function test_gift_aid_declaration_is_sent_to_the_website(): void
    {
        $this->fakeChurch();

        Livewire::test(Give::class)
            ->call('toggleGiftAid')
            ->set('firstName', 'Ada')
            ->set('lastName', 'Obi')
            ->set('address', '1 High St')
            ->set('postcode', 'S1 1AA')
            ->set('phone', '0114 000')
            ->set('email', 'ada@example.com')
            ->set('giftAidCode', 'AO-tithe')
            ->set('confirmed', true)
            ->call('submitGiftAid')
            ->assertHasNoErrors()
            ->assertSet('giftAidSent', true)
            ->assertSee('Gift Aid declaration has been received');

        Http::assertSent(fn ($request) => str_ends_with($request->url(), '/gift-aid') && $request['gift_aid_code'] === 'AO-tithe' && $request['confirmed'] === true);
    }

    public function test_a_website_error_keeps_the_declaration_form_open(): void
    {
        Http::fake(['site.test/api/v1/gift-aid' => Http::response(['message' => 'Server error'], 500)]);
        $this->fakeChurch();

        Livewire::test(Give::class)
            ->call('toggleGiftAid')
            ->set('firstName', 'Ada')->set('lastName', 'Obi')->set('address', '1 High St')->set('postcode', 'S1 1AA')
            ->set('phone', '0114 000')->set('email', 'ada@example.com')->set('giftAidCode', 'AO-tithe')->set('confirmed', true)
            ->call('submitGiftAid')
            ->assertSet('giftAidSent', false)
            ->assertSet('giftAidOpen', true)
            ->assertNotSet('error', null);
    }
}
