<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Services\EnrollmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseCheckout extends Component
{
    public Course $course;

    /**
     * Mount the component.
     */
    public function mount(Course $course, EnrollmentService $enrollmentService): void
    {
        $this->course = $course;

        // Check if user already has access
        if ($enrollmentService->checkAccess(Auth::user(), $course)) {
            session()->flash('message', 'Ya tienes este curso.');
            $this->redirect(route('student.dashboard'));
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.course-checkout');
    }
}

