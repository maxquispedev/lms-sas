<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Enums\CourseStatus;
use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CoursesCatalog extends Component
{
    /**
     * Get all published courses available for purchase.
     */
    #[Computed]
    public function courses(): Collection
    {
        $user = Auth::user();
        
        $courses = Course::where('status', CourseStatus::Published)
            ->with(['teacher', 'modules.lessons'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Check if user is enrolled in each course
        if ($user) {
            $enrolledCourseIds = $user
                ->courses()
                ->wherePivot('status', 'active')
                ->pluck('courses.id')
                ->toArray();

            $courses->each(function (Course $course) use ($enrolledCourseIds): void {
                $course->is_enrolled = in_array($course->id, $enrolledCourseIds, true);
            });
        } else {
            $courses->each(function (Course $course): void {
                $course->is_enrolled = false;
            });
        }

        return $courses;
    }

    /**
     * Render the courses catalog view.
     */
    public function render(): View
    {
        return view('livewire.courses-catalog');
    }
}
