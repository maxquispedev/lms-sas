<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentDashboard extends Component
{
    /**
     * Get the enrolled courses for the authenticated user.
     */
    #[Computed]
    public function courses(): Collection
    {
        $user = Auth::user();
        
        if (!$user) {
            return collect();
        }

        return $user
            ->courses()
            ->wherePivot('status', 'active')
            ->with('teacher')
            ->orderByPivot('enrolled_at', 'desc')
            ->get();
    }

    /**
     * Render the student dashboard view.
     */
    public function render(): View
    {
        return view('livewire.student-dashboard');
    }
}

