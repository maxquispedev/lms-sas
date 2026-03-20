<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Course;
use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\ExamAttemptAnswer;
use App\Models\ExamQuestion;
use App\Services\EnrollmentService;
use App\Services\ExamEligibilityService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class TakeExam extends Component
{
    public Course $course;

    public Exam $exam;

    public ExamAttempt $attempt;

    /** @var Collection<int, ExamQuestion> */
    public Collection $questions;

    /** @var array<int, int> question_id => option_id */
    public array $selectedOptions = [];

    public string $step = 'take';

    public float $scorePercent = 0;

    public bool $passed = false;

    /** @var list<array{question: string, module: ?string, lesson: ?string}> */
    public array $wrongReview = [];

    public function mount(
        EnrollmentService $enrollmentService,
        ExamEligibilityService $eligibility,
        Course $course,
        Exam $exam,
    ): void {
        $user = Auth::user();

        if (! $enrollmentService->checkAccess($user, $course)) {
            abort(403, 'No tienes acceso a este curso.');
        }

        if ($exam->course_id !== $course->id || ! $exam->is_published) {
            abort(404);
        }

        $this->course = $course;
        $this->exam = $exam->load(['questions.module', 'questions.lesson']);

        if ($eligibility->hasPassed($user, $exam)) {
            $this->redirect(route('course.exams', $course), navigate: true);

            return;
        }

        if ($eligibility->cooldownEndsAt($user, $exam) !== null) {
            session()->flash('error', 'Debes esperar el tiempo indicado antes de volver a intentar este examen.');

            $this->redirect(route('course.exams', $course), navigate: true);

            return;
        }

        $rawQuestions = $exam->questions()->with('options')->orderBy('sort_order')->get();
        if ($rawQuestions->isEmpty()) {
            abort(404, 'Este examen no tiene preguntas.');
        }

        $this->questions = $rawQuestions->map(function (ExamQuestion $q): ExamQuestion {
            $shuffled = $q->options->shuffle()->values();
            $q->setRelation('options', $shuffled);

            return $q;
        })->values();

        $existingAttempt = ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->whereNull('submitted_at')
            ->orderByDesc('id')
            ->first();

        if ($existingAttempt) {
            $this->attempt = $existingAttempt;
        } else {
            $this->attempt = ExamAttempt::create([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'started_at' => now(),
            ]);
        }
    }

    /**
     * @return list<array{question: string, module: ?string, lesson: ?string}>
     */
    private function buildWrongReview(ExamAttempt $attempt): array
    {
        $attempt->load([
            'answers.question.module',
            'answers.question.lesson',
        ]);

        $out = [];
        foreach ($attempt->answers as $answer) {
            if ($answer->is_correct) {
                continue;
            }
            $q = $answer->question;
            if ($q === null) {
                continue;
            }
            $out[] = [
                'question' => $q->question_text,
                'module' => $q->module?->title,
                'lesson' => $q->lesson?->title,
            ];
        }

        return $out;
    }

    public function submit(): void
    {
        if ($this->step !== 'take') {
            return;
        }

        $this->attempt->refresh();
        if ($this->attempt->submitted_at !== null) {
            return;
        }

        $rules = [];
        foreach ($this->questions as $question) {
            $rules['selectedOptions.'.$question->id] = [
                'required',
                'integer',
                'exists:exam_question_options,id',
            ];
        }

        $this->validate($rules, [
            'selectedOptions.*.required' => 'Responde todas las preguntas.',
        ]);

        foreach ($this->questions as $question) {
            $optionId = (int) $this->selectedOptions[$question->id];
            $belongs = $question->options->contains('id', $optionId);
            if (! $belongs) {
                $this->addError('selectedOptions.'.$question->id, 'Opción no válida para esta pregunta.');

                return;
            }
        }

        $total = $this->questions->count();
        $correctCount = 0;

        foreach ($this->questions as $question) {
            $selectedId = (int) $this->selectedOptions[$question->id];
            $correctId = $question->correctOptionId();
            $isCorrect = $correctId !== null && $selectedId === $correctId;
            if ($isCorrect) {
                $correctCount++;
            }

            ExamAttemptAnswer::query()->updateOrCreate(
                [
                    'exam_attempt_id' => $this->attempt->id,
                    'exam_question_id' => $question->id,
                ],
                [
                    'exam_question_option_id' => $selectedId,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        $score = $total > 0 ? round(($correctCount / $total) * 100, 2) : 0.0;
        $passed = $score >= $this->exam->passing_score_percent;

        $this->attempt->update([
            'submitted_at' => now(),
            'score_percent' => $score,
            'passed' => $passed,
            'cooldown_until' => $passed ? null : now()->addMinutes(max(1, $this->exam->cooldown_minutes)),
        ]);

        $this->attempt->refresh();

        $this->scorePercent = $score;
        $this->passed = $passed;
        $this->wrongReview = $passed ? [] : $this->buildWrongReview($this->attempt);
        $this->step = 'results';
    }

    public function render(): View
    {
        return view('livewire.take-exam');
    }
}
