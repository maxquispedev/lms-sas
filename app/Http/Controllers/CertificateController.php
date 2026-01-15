<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CertificateController extends Controller
{
    /**
     * Descarga el certificado de un curso completado.
     *
     * @param Course $course El curso del cual se descargará el certificado
     * @return Response|RedirectResponse El PDF del certificado o una redirección con error
     */
    public function download(Course $course): Response|RedirectResponse
    {
        $user = Auth::user();

        // Verificar si el usuario ha completado el 100% del curso
        $course->load('modules.lessons', 'teacher');
        $allLessonIds = $course->modules
            ->flatMap->lessons
            ->pluck('id')
            ->toArray();

        $completedLessonIds = $user
            ->lessons_completed()
            ->pluck('lessons.id')
            ->toArray();

        $totalLessons = count($allLessonIds);
        $completedCount = count(array_intersect($allLessonIds, $completedLessonIds));

        // Si no ha completado todas las lecciones, redirigir con error
        if ($completedCount < $totalLessons || $totalLessons === 0) {
            return redirect()->back()
                ->with('error', 'Debes completar todas las lecciones');
        }

        // Generar el PDF del certificado
        $date = now()->format('d/m/Y');

        $pdf = Pdf::loadView('certificates.default', [
            'user' => $user,
            'course' => $course,
            'date' => $date,
        ])
            ->setPaper('a4', 'landscape');

        $filename = "Certificado-{$course->title}.pdf";

        return $pdf->download($filename);
    }
}
