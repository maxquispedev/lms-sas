<?php

use App\Http\Controllers\Api\CourseApiController;
use Illuminate\Support\Facades\Route;

Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{course:slug}', [CourseApiController::class, 'show']);

