<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class ForgotPassword extends Component
{
    public string $email = '';

    public bool $emailSent = false;

    /**
     * Send password reset link.
     */
    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.exists' => 'No encontramos una cuenta con ese correo electrónico.',
        ]);

        $status = Password::broker('users')->sendResetLink(
            ['email' => $this->email]
        );

        if ($status === Password::RESET_LINK_SENT) {
            $this->emailSent = true;
            session()->flash('status', 'Hemos enviado un enlace de recuperación a tu correo electrónico.');
        } else {
            $this->addError('email', 'No pudimos enviar el enlace de recuperación. Por favor, intenta nuevamente.');
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.auth.forgot-password');
    }
}
