<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\CertificateCodeIssuer;
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
    public function download(Course $course, CertificateCodeIssuer $certificateCodeIssuer): Response|RedirectResponse
    {
        $user = Auth::user();

        $course->load('modules.lessons');

        $isEnrolled = $user
            ->courses()
            ->where('courses.id', $course->id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$isEnrolled) {
            return redirect()->back()
                ->with('error', 'No estás inscrito en este curso.');
        }
        $hasLessons = $course->modules->flatMap->lessons->isNotEmpty();

        if ($hasLessons) {
            // Curso con lecciones: exigir 100% de lecciones completadas
            $allLessonIds = $course->modules
                ->flatMap->lessons
                ->pluck('id')
                ->toArray();

            $completedLessonIds = $user
                ->lessons_completed()
                ->pluck('lessons.id')
                ->toArray();

            $total = count($allLessonIds);
            $completed = count(array_intersect($allLessonIds, $completedLessonIds));

            if ($total === 0 || $completed < $total) {
                return redirect()->back()
                    ->with('error', 'Debes completar todas las lecciones');
            }
        } else {
            // Curso solo con módulos: exigir 100% de módulos completados
            $allModuleIds = $course->modules->pluck('id')->toArray();

            $completedModuleIds = $user
                ->modules_completed()
                ->pluck('modules.id')
                ->toArray();

            $total = count($allModuleIds);
            $completed = count(array_intersect($allModuleIds, $completedModuleIds));

            if ($total === 0 || $completed < $total) {
                return redirect()->back()
                    ->with('error', 'Debes completar todos los módulos');
            }
        }

        $certificateCode = $certificateCodeIssuer->getOrCreateForEnrollment($user, $course);

        // Generar el PDF del certificado
        $date = now()->format('d/m/Y');
        $backgroundPath = base_path('resources/views/certificates/modelo-certificado.jpg');
        $backgroundImage = '';
        if (file_exists($backgroundPath)) {
            $mime = mime_content_type($backgroundPath);
            $backgroundImage = 'data:' . $mime . ';base64,' . base64_encode((string) file_get_contents($backgroundPath));
        }

        $pdf = Pdf::loadView('certificates.default', [
            'user' => $user,
            'course' => $course,
            'date' => $date,
            'backgroundImage' => $backgroundImage,
            'certificateCode' => $certificateCode,
        ])
            ->setPaper('a4', 'landscape');

        $filename = "Certificado-{$course->title}.pdf";

        return $pdf->download($filename);
    }
}
