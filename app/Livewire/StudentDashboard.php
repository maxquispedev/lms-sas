<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
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

        if (! $user) {
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
                } else {
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
                }

                $publishedExamIds = Exam::query()
                    ->where('course_id', $course->id)
                    ->where('is_published', true)
                    ->pluck('id');

                $course->published_exams_count = $publishedExamIds->count();
                $course->passed_exams_count = $publishedExamIds->isEmpty()
                    ? 0
                    : ExamAttempt::query()
                        ->where('user_id', $user->id)
                        ->whereIn('exam_id', $publishedExamIds)
                        ->where('passed', true)
                        ->distinct('exam_id')
                        ->count('exam_id');
                $course->exam_requirement_met = $course->published_exams_count > 0
                    && $course->passed_exams_count === $course->published_exams_count;
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
