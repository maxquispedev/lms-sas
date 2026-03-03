<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\CourseStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CourseApiResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;

class CourseApiController extends Controller
{
    /**
     * Display a listing of published courses.
     */
    public function index(): JsonResponse
    {
        $courses = Course::where('status', CourseStatus::Published)
            ->with(['teacher', 'modules.lessons', 'categories'])
            ->get();

        return response()->json([
            'data' => CourseApiResource::collection($courses),
        ]);
    }

    /**
     * Display the specified course.
     */
    public function show(Course $course): JsonResponse
    {
        // Only show published courses
        if ($course->status !== CourseStatus::Published) {
            abort(404, 'Course not found.');
        }

        $course->load(['teacher', 'modules.lessons', 'categories']);

        return response()->json([
            'data' => new CourseApiResource($course),
        ]);
    }
}

