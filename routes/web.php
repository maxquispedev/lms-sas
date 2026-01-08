<?php

use App\Livewire\StudentDashboard;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-courses', StudentDashboard::class)
        ->name('student.dashboard');
});
