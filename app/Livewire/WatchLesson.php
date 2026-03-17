<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
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
    public ?Module $currentModule = null;
    public bool $autoplay = false;
    public bool $hasLessons = false;

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

        $this->hasLessons = $this->course->modules
            ->flatMap->lessons
            ->isNotEmpty();

        // Resolver: item puede ser slug de lección (modo lecciones) o slug de módulo (modo módulos)
        if ($this->hasLessons) {
            if ($lesson) {
                $this->currentLesson = $course->modules
                    ->flatMap->lessons
                    ->firstWhere('slug', $lesson);

                if (! $this->currentLesson) {
                    abort(404, 'Lección no encontrada.');
                }
            } else {
                // Primera lección del primer módulo ordenado
                $firstModule = $course->modules
                    ->sortBy('sort_order')
                    ->first();

                if ($firstModule && $firstModule->lessons->isNotEmpty()) {
                    $this->currentLesson = $firstModule->lessons
                        ->sortBy('sort_order')
                        ->first();
                }
            }
        }

        // Modo módulos (cuando no hay lecciones)
        if (! $this->hasLessons) {
            if ($lesson) {
                $this->currentModule = $this->course->modules
                    ->firstWhere('slug', $lesson);

                if (! $this->currentModule) {
                    abort(404, 'Módulo no encontrado.');
                }
            } else {
                $this->currentModule = $this->course->modules
                    ->sortBy('sort_order')
                    ->first();
            }
        }

        if (! $this->currentLesson && ! $this->currentModule) {
            abort(404, 'No hay contenido disponible en este curso.');
        }

        // Ensure module relationship is loaded cuando hay lección
        if ($this->currentLesson) {
            $this->currentLesson->load('module');
        }
    }

    /**
     * Toggle the completion status of the current lesson.
     */
    public function toggleComplete(): void
    {
        $user = Auth::user();
        $isCompleted = $this->isCompleted();

        if ($this->hasLessons) {
            if (! $this->currentLesson) {
                return;
            }

            $user->lessons()->syncWithoutDetaching([
                $this->currentLesson->id => [
                    'completed' => ! $isCompleted,
                    'completed_at' => ! $isCompleted ? now() : null,
                ],
            ]);
        } else {
            if (! $this->currentModule) {
                return;
            }

            $user->modules()->syncWithoutDetaching([
                $this->currentModule->id => [
                    'completed' => ! $isCompleted,
                    'completed_at' => ! $isCompleted ? now() : null,
                ],
            ]);
        }

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
        if (! $this->hasLessons) {
            return false;
        }

        return Auth::user()
            ->lessons_completed()
            ->where('lessons.id', $this->currentLesson->id)
            ->exists();
    }

    public function isModuleCompleted(): bool
    {
        if ($this->hasLessons) {
            return false;
        }

        return Auth::user()
            ->modules_completed()
            ->where('modules.id', $this->currentModule->id)
            ->exists();
    }

    public function isCompleted(): bool
    {
        return $this->hasLessons
            ? $this->isLessonCompleted()
            : $this->isModuleCompleted();
    }

    /**
     * Get the next lesson in the course.
     */
    public function getNextLesson(): ?Lesson
    {
        if (! $this->hasLessons) {
            return null;
        }

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
     * Get the previous lesson in the course.
     */
    public function getPreviousLesson(): ?Lesson
    {
        if (! $this->hasLessons) {
            return null;
        }

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

        if ($currentIndex !== false && $currentIndex > 0) {
            return $allLessons->get($currentIndex - 1);
        }

        return null;
    }

    public function getNextModule(): ?Module
    {
        $modules = $this->course->modules
            ->sortBy('sort_order')
            ->values();

        $currentIndex = $modules->search(function ($module) {
            return $this->currentModule !== null && $module->id === $this->currentModule->id;
        });

        if ($currentIndex !== false && $currentIndex < $modules->count() - 1) {
            return $modules->get($currentIndex + 1);
        }

        return null;
    }

    public function getPreviousModule(): ?Module
    {
        $modules = $this->course->modules
            ->sortBy('sort_order')
            ->values();

        $currentIndex = $modules->search(function ($module) {
            return $this->currentModule !== null && $module->id === $this->currentModule->id;
        });

        if ($currentIndex !== false && $currentIndex > 0) {
            return $modules->get($currentIndex - 1);
        }

        return null;
    }

    /**
     * Calculate the course progress percentage.
     */
    public function getCourseProgress(): int
    {
        $user = Auth::user();

        if ($this->hasLessons) {
            $allLessonIds = $this->course->modules
                ->flatMap->lessons
                ->pluck('id')
                ->toArray();

            $completedLessonIds = $user
                ->lessons_completed()
                ->pluck('lessons.id')
                ->toArray();

            $total = count($allLessonIds);
            $completed = count(array_intersect($allLessonIds, $completedLessonIds));

            if ($total === 0) {
                return 0;
            }

            return (int) floor(($completed / $total) * 100);
        }

        $allModuleIds = $this->course->modules
            ->pluck('id')
            ->toArray();

        $completedModuleIds = $user
            ->modules_completed()
            ->pluck('modules.id')
            ->toArray();

        $total = count($allModuleIds);
        $completed = count(array_intersect($allModuleIds, $completedModuleIds));

        if ($total === 0) {
            return 0;
        }

        return (int) floor(($completed / $total) * 100);
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

        $completedModuleIds = Auth::user()
            ->modules_completed()
            ->pluck('modules.id')
            ->toArray();

        $courseProgress = $this->getCourseProgress();

        return view('livewire.watch-lesson', [
            'modules' => $modules,
            'completedLessonIds' => $completedLessonIds,
            'nextLesson' => $this->getNextLesson(),
            'previousLesson' => $this->getPreviousLesson(),
            'nextModule' => $this->getNextModule(),
            'previousModule' => $this->getPreviousModule(),
            'courseProgress' => $courseProgress,
            'hasLessons' => $this->hasLessons,
            'currentModule' => $this->currentModule,
            'completedModuleIds' => $completedModuleIds,
        ]);
    }
}

