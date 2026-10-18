<?php

namespace App\Livewire;

use App\Support\ChurchData;
use App\Support\ContentApi;
use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Give extends Component
{
    /** @var array<string, mixed> */
    #[Locked]
    public array $giving = [];

    public bool $giftAidOpen = false;

    public bool $giftAidSent = false;

    public string $firstName = '';

    public string $lastName = '';

    public string $address = '';

    public string $postcode = '';

    public string $phone = '';

    public string $email = '';

    public string $giftAidCode = '';

    public string $declarationDate = '';

    public bool $confirmed = false;

    public ?string $error = null;

    public function mount(): void
    {
        $this->giving = ChurchData::get()['giving'];
        $this->declarationDate = now()->toDateString();

        if ($member = MemberSession::member()) {
            $this->firstName = $member['first_name'];
            $this->lastName = $member['last_name'];
            $this->email = $member['email'];
            $this->phone = (string) ($member['phone'] ?? '');
        }
    }

    public function openCard()
    {
        return ChurchData::open($this->giving['card_url'] ?? null);
    }

    public function openPayPal()
    {
        return ChurchData::open($this->giving['paypal_url'] ?? null);
    }

    public function toggleGiftAid(): void
    {
        $this->giftAidOpen = ! $this->giftAidOpen;
    }

    public function submitGiftAid(): void
    {
        $this->error = null;

        $this->validate([
            'firstName' => ['required', 'string', 'max:100'],
            'lastName' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:20'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'giftAidCode' => ['required', 'string', 'max:50'],
            'declarationDate' => ['required', 'date'],
            'confirmed' => ['accepted'],
        ], attributes: ['giftAidCode' => 'Gift Aid code', 'declarationDate' => 'declaration date']);

        $result = ContentApi::send('POST', 'gift-aid', [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'phone' => $this->phone,
            'email' => $this->email,
            'gift_aid_code' => $this->giftAidCode,
            'declaration_date' => $this->declarationDate,
            'confirmed' => true,
        ]);

        if (! $result['ok']) {
            $this->error = $result['message'];

            return;
        }

        $this->giftAidSent = true;
        $this->giftAidOpen = false;
    }

    public function render()
    {
        return view('livewire.give');
    }
}
