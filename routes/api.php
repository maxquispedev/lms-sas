<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CourseApiController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryApiController::class, 'index']);
Route::get('/courses', [CourseApiController::class, 'index']);
Route::get('/courses/{course:slug}', [CourseApiController::class, 'show']);

