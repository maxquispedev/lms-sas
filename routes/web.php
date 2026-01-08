<?php

use App\Livewire\CourseCheckout;
use App\Livewire\StudentDashboard;
use App\Livewire\WatchLesson;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-courses', StudentDashboard::class)
        ->name('student.dashboard');

    Route::get('/learn/{course:slug}/{lesson:slug?}', WatchLesson::class)
        ->name('course.learn');

    Route::get('/checkout/{course:slug}', CourseCheckout::class)
        ->name('course.checkout');
});
