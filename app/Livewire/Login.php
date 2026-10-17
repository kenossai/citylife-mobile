<?php

namespace App\Livewire;

use App\Support\MemberSession;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Native\Mobile\Facades\Browser;

#[Layout('components.layouts.main')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public ?string $error = null;

    public function mount()
    {
        if (MemberSession::check()) {
            return $this->redirectRoute('home', navigate: true);
        }
    }

    public function signIn()
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $result = MemberSession::login($this->email, $this->password);

        if (! $result['ok']) {
            $this->error = $result['message'];
            $this->password = '';

            return null;
        }

        return $this->redirectRoute('home', navigate: true);
    }

    public function openForgotPassword()
    {
        $url = rtrim(config('services.citylife.url'), '/').'/member/forgot-password';

        return Browser::inApp($url) ? null : redirect()->away($url);
    }

    public function render()
    {
        return view('livewire.login');
    }
}
