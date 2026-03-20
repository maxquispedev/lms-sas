<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Exam;
use App\Services\EnrollmentService;
use App\Services\ExamEligibilityService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CourseExams extends Component
{
    public Course $course;

    /** @var Collection<int, array{exam: Exam, passed: bool, cooldown_ends_at: ?string}> */
    public Collection $examRows;

    public function mount(
        EnrollmentService $enrollmentService,
        ExamEligibilityService $eligibility,
        Course $course,
    ): void {
        if (! $enrollmentService->checkAccess(Auth::user(), $course)) {
            abort(403, 'No tienes acceso a este curso.');
        }

        $this->course = $course;

        $exams = Exam::query()
            ->where('course_id', $course->id)
            ->where('is_published', true)
            ->withCount('questions')
            ->orderBy('sort_order')
            ->get();

        $user = Auth::user();

        $this->examRows = $exams->map(function (Exam $exam) use ($user, $eligibility): array {
            $passed = $eligibility->hasPassed($user, $exam);
            $cooldown = $eligibility->cooldownEndsAt($user, $exam);

            return [
                'exam' => $exam,
                'passed' => $passed,
                'cooldown_ends_at' => $cooldown?->toIso8601String(),
            ];
        });
    }

    public function render(): View
    {
        return view('livewire.course-exams');
    }
}
