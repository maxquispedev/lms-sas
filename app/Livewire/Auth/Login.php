<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    /**
     * Handle user login.
     */
    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ])) {
            $this->addError('email', 'Credenciales inválidas.');
            return;
        }

        session()->regenerate();

        $this->redirectIntended(route('student.dashboard'), navigate: true);
    }

    public function render(): View
    {
        return view('livewire.auth.login');
    }
}

