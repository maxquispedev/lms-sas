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
            ->each(function (Course $course) use ($completedLessonIds, $user): void {
                $lessonIds = $course->modules
                    ->flatMap->lessons
                    ->pluck('id');

                $hasLessons = $lessonIds->isNotEmpty();

                if ($hasLessons) {
                    $total = $lessonIds->count();
                    $completedCount = $lessonIds
                        ->filter(fn (int $lessonId): bool => in_array($lessonId, $completedLessonIds, true))
                        ->count();

                    $course->progress_total_label = 'lecciones';
                    $course->total_progress_items = $total;
                    $course->completed_progress_items = $completedCount;
                    $course->progress = $total > 0
                        ? (int) floor(($completedCount / $total) * 100)
                        : 0;

                    return;
                }

                $moduleIds = $course->modules
                    ->pluck('id');

                $completedModuleIds = $user
                    ->modules_completed()
                    ->pluck('modules.id')
                    ->toArray();

                $total = $moduleIds->count();
                $completedCount = $moduleIds
                    ->filter(fn (int $moduleId): bool => in_array($moduleId, $completedModuleIds, true))
                    ->count();

                $course->progress_total_label = 'módulos';
                $course->total_progress_items = $total;
                $course->completed_progress_items = $completedCount;
                $course->progress = $total > 0
                    ? (int) floor(($completedCount / $total) * 100)
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

