<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Carbon\Carbon;

class EnrollmentService
{
    /**
     * Enroll a user in a course.
     *
     * @param User $user The user to enroll
     * @param Course $course The course to enroll the user in
     * @return void
     */
    public function enrollUser(User $user, Course $course): void
    {
        $user->courses()->syncWithoutDetaching([
            $course->id => [
                'status' => 'active',
                'enrolled_at' => Carbon::now(),
            ],
        ]);
    }

    /**
     * Check if a user has access to a course.
     *
     * @param User $user The user to check
     * @param Course $course The course to check access for
     * @return bool True if the user has active access, false otherwise
     */
    public function checkAccess(User $user, Course $course): bool
    {
        $enrollment = $user->courses()
            ->where('courses.id', $course->id)
            ->wherePivot('status', 'active')
            ->first();

        if (!$enrollment) {
            return false;
        }

        $pivot = $enrollment->pivot;

        // Check if expires_at is null or a future date
        if ($pivot->expires_at !== null) {
            $expiresAt = Carbon::parse($pivot->expires_at);
            if ($expiresAt->isPast()) {
                return false;
            }
        }

        return true;
    }
}

