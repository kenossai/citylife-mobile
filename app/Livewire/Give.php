<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.main')]
class Give extends Component
{
    public const AMOUNTS = [10, 25, 50, 100, 250, 500];

    public const FUNDS = ['General Fund', 'Tithes', 'Missions', 'Building Fund', 'Youth Ministry'];

    public const FREQUENCIES = ['once' => 'One-time', 'weekly' => 'Weekly', 'monthly' => 'Monthly'];

    public ?int $preset = 25;

    public string $custom = '';

    public string $fund = 'General Fund';

    public string $frequency = 'once';

    public bool $given = false;

    public function choose(int $amount): void
    {
        if (in_array($amount, self::AMOUNTS, true)) {
            $this->preset = $amount;
            $this->custom = '';
        }
    }

    public function updatedCustom(): void
    {
        $this->preset = null;
    }

    public function getAmountProperty(): float
    {
        return $this->custom !== '' ? (float) $this->custom : (float) $this->preset;
    }

    public function give(): void
    {
        $this->validate([
            'custom' => ['nullable', 'numeric', 'min:1', 'max:100000'],
            'fund' => ['required', 'in:'.implode(',', self::FUNDS)],
            'frequency' => ['required', 'in:'.implode(',', array_keys(self::FREQUENCIES))],
        ]);

        if ($this->amount < 1) {
            $this->addError('custom', 'Please choose or enter an amount.');

            return;
        }

        $this->given = true;
    }

    public function another(): void
    {
        $this->reset('given', 'custom', 'fund', 'frequency');
        $this->preset = 25;
    }

    public function render()
    {
        return view('livewire.give');
    }
}
