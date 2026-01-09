<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Services\EnrollmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class WatchLesson extends Component
{
    public Course $course;
    public ?Lesson $currentLesson = null;
    public bool $autoplay = false;

    /**
     * Mount the component.
     */
    public function mount(
        EnrollmentService $enrollmentService,
        Course $course,
        ?string $lesson = null,
    ): void
    {
        // Check access
        if (!$enrollmentService->checkAccess(Auth::user(), $course)) {
            abort(403, 'No tienes acceso a este curso.');
        }

        $this->course = $course->load('modules.lessons');

        // Resolve lesson
        if ($lesson) {
            $this->currentLesson = $course->modules
                ->flatMap->lessons
                ->firstWhere('slug', $lesson);

            if (!$this->currentLesson) {
                abort(404, 'Lección no encontrada.');
            }
        } else {
            // Load first lesson of first module (ordered by sort_order)
            $firstModule = $course->modules
                ->sortBy('sort_order')
                ->first();

            if ($firstModule && $firstModule->lessons->isNotEmpty()) {
                $this->currentLesson = $firstModule->lessons
                    ->sortBy('sort_order')
                    ->first();
            }
        }

        if (!$this->currentLesson) {
            abort(404, 'No hay lecciones disponibles en este curso.');
        }

        // Ensure module relationship is loaded
        $this->currentLesson->load('module');
    }

    /**
     * Toggle the completion status of the current lesson.
     */
    public function toggleComplete(): void
    {
        $user = Auth::user();
        $isCompleted = $this->isLessonCompleted();

        // Always use syncWithoutDetaching to ensure the pivot record exists
        $user->lessons()->syncWithoutDetaching([
            $this->currentLesson->id => [
                'completed' => !$isCompleted,
                'completed_at' => !$isCompleted ? now() : null,
            ],
        ]);

        // Refresh component state
        $this->dispatch('lesson-completion-toggled');
    }

    /**
     * Toggle autoplay preference.
     */
    public function toggleAutoplay(): void
    {
        $this->autoplay = !$this->autoplay;
    }

    /**
     * Check if the current lesson is completed by the user.
     */
    public function isLessonCompleted(): bool
    {
        return Auth::user()
            ->lessons_completed()
            ->where('lessons.id', $this->currentLesson->id)
            ->exists();
    }

    /**
     * Get the next lesson in the course.
     */
    public function getNextLesson(): ?Lesson
    {
        $modules = $this->course->modules
            ->sortBy('sort_order');

        $allLessons = $modules
            ->flatMap(function ($module) {
                return $module->lessons->sortBy('sort_order');
            })
            ->values();

        $currentIndex = $allLessons->search(function ($lesson) {
            return $lesson->id === $this->currentLesson->id;
        });

        if ($currentIndex !== false && $currentIndex < $allLessons->count() - 1) {
            return $allLessons->get($currentIndex + 1);
        }

        return null;
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        $modules = $this->course->modules
            ->sortBy('sort_order')
            ->map(function ($module) {
                $module->lessons = $module->lessons->sortBy('sort_order');
                return $module;
            });

        $completedLessonIds = Auth::user()
            ->lessons_completed()
            ->pluck('lessons.id')
            ->toArray();

        // Load teacher relationship
        $this->course->load('teacher');

        return view('livewire.watch-lesson', [
            'modules' => $modules,
            'completedLessonIds' => $completedLessonIds,
            'nextLesson' => $this->getNextLesson(),
        ]);
    }
}

