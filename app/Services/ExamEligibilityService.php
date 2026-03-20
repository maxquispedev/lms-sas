<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Carbon\CarbonInterface;

/**
 * Reglas de acceso a exámenes: aprobación previa y cooldown tras reprobar.
 */
final class ExamEligibilityService
{
    public function hasPassed(User $user, Exam $exam): bool
    {
        return ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->where('passed', true)
            ->exists();
    }

    /**
     * Si el alumno está en cooldown por un intento fallido reciente, devuelve cuándo puede reintentar.
     */
    public function cooldownEndsAt(User $user, Exam $exam): ?CarbonInterface
    {
        $attempt = ExamAttempt::query()
            ->where('user_id', $user->id)
            ->where('exam_id', $exam->id)
            ->whereNotNull('submitted_at')
            ->where('passed', false)
            ->whereNotNull('cooldown_until')
            ->orderByDesc('submitted_at')
            ->first();

        if ($attempt === null || $attempt->cooldown_until === null) {
            return null;
        }

        if ($attempt->cooldown_until->isPast()) {
            return null;
        }

        return $attempt->cooldown_until;
    }

    public function canStartAttempt(User $user, Exam $exam): bool
    {
        if ($this->hasPassed($user, $exam)) {
            return false;
        }

        return $this->cooldownEndsAt($user, $exam) === null;
    }
}
