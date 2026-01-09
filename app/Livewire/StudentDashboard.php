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

        $completedLessonIds = $user
            ->lessons_completed()
            ->pluck('lessons.id')
            ->toArray();

        return $user
            ->courses()
            ->wherePivot('status', 'active')
            ->with([
                'teacher',
                'modules.lessons',
            ])
            ->orderByPivot('enrolled_at', 'desc')
            ->get()
            ->each(function (Course $course) use ($completedLessonIds): void {
                $lessonIds = $course->modules
                    ->flatMap->lessons
                    ->pluck('id');

                $totalLessons = $lessonIds->count();
                $completedCount = $lessonIds
                    ->filter(fn (int $lessonId): bool => in_array($lessonId, $completedLessonIds, true))
                    ->count();

                $course->total_lessons = $totalLessons;
                $course->completed_lessons = $completedCount;
                $course->progress = $totalLessons > 0
                    ? (int) floor(($completedCount / $totalLessons) * 100)
                    : 0;
            });
    }

    /**
     * Render the student dashboard view.
     */
    public function render(): View
    {
        return view('livewire.student-dashboard');
    }
}

