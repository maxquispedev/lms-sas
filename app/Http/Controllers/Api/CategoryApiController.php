<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryApiResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryApiController extends Controller
{
    /**
     * Lista todas las categorías (para filtros y áreas de capacitación).
     */
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('sort_order')->orderBy('name')->get();

        return response()->json([
            'data' => CategoryApiResource::collection($categories),
        ]);
    }
}
