<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentProfile extends Component
{
    public string $name = '';
    public string $last_name = '';
    public string $email = '';
    public string $current_password = '';
    public string $new_password = '';
    public string $new_password_confirmation = '';

    /**
     * Mount the component and load user data.
     */
    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->last_name = $user->last_name ?? '';
        $this->email = $user->email;
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
        ]);

        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'last_name' => $this->last_name,
        ]);

        session()->flash('profile_updated', 'Perfil actualizado correctamente.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'string'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'La contraseña actual es obligatoria.',
            'new_password.required' => 'La nueva contraseña es obligatoria.',
            'new_password.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
            'new_password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';

        session()->flash('password_updated', 'Contraseña actualizada correctamente.');
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.student-profile');
    }
}

