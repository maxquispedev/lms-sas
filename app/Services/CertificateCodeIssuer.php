<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Genera y persiste un código único de certificado por inscripción (user + course).
 * Si ya existe en `course_user`, se reutiliza en descargas posteriores.
 */
final class CertificateCodeIssuer
{
    private const PREFIX = 'SEIA';

    private const RANDOM_LENGTH = 10;

    private const MAX_ATTEMPTS = 12;

    /**
     * Devuelve el código del certificado para la inscripción del usuario al curso.
     *
     * @throws \RuntimeException Si no se pudo asignar un código tras varios intentos
     */
    public function getOrCreateForEnrollment(User $user, Course $course): string
    {
        $existing = $this->fetchCode($user->id, $course->id);
        if ($existing !== null && $existing !== '') {
            return $existing;
        }

        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            $code = $this->generateCode();

            try {
                $updated = DB::table('course_user')
                    ->where('user_id', $user->id)
                    ->where('course_id', $course->id)
                    ->where(function ($query): void {
                        $query->whereNull('certificate_code')->orWhere('certificate_code', '');
                    })
                    ->update(['certificate_code' => $code]);

                if ($updated > 0) {
                    return $code;
                }
            } catch (QueryException $e) {
                if ($this->isUniqueViolation($e)) {
                    $afterConflict = $this->fetchCode($user->id, $course->id);
                    if ($afterConflict !== null && $afterConflict !== '') {
                        return $afterConflict;
                    }

                    continue;
                }

                throw $e;
            }

            $afterRace = $this->fetchCode($user->id, $course->id);
            if ($afterRace !== null && $afterRace !== '') {
                return $afterRace;
            }
        }

        throw new \RuntimeException('No se pudo generar un código único para el certificado.');
    }

    private function fetchCode(int $userId, int $courseId): ?string
    {
        $value = DB::table('course_user')
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->value('certificate_code');

        if ($value === null || $value === '') {
            return null;
        }

        return (string) $value;
    }

    /**
     * Formato: SEIA-2026-XXXXXXXXXX (alfanumérico en mayúsculas).
     */
    private function generateCode(): string
    {
        return sprintf(
            '%s-%s-%s',
            self::PREFIX,
            now()->format('Y'),
            strtoupper(Str::random(self::RANDOM_LENGTH))
        );
    }

    private function isUniqueViolation(QueryException $e): bool
    {
        $sqlState = (string) ($e->errorInfo[0] ?? '');
        // MySQL / MariaDB: 23000; PostgreSQL: 23505
        if (in_array($sqlState, ['23000', '23505'], true)) {
            return true;
        }

        return str_contains(strtolower($e->getMessage()), 'duplicate');
    }
}
