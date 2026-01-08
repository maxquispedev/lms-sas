<?php

declare(strict_types=1);

namespace App\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class StudentDashboard extends Component
{
    /**
     * Render the student dashboard view.
     */
    public function render(): View
    {
        $courses = Auth::user()
            ->courses()
            ->wherePivot('status', 'active')
            ->with('teacher')
            ->get();

        return view('livewire.student-dashboard', [
            'courses' => $courses,
        ]);
    }
}

