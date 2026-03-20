<?php

use App\Http\Controllers\CertificateController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\CourseCheckout;
use App\Livewire\CourseExams;
use App\Livewire\CoursesCatalog;
use App\Livewire\PaymentSuccess;
use App\Livewire\StudentDashboard;
use App\Livewire\StudentProfile;
use App\Livewire\TakeExam;
use App\Livewire\WatchLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)
        ->name('login');

    Route::get('/forgot-password', ForgotPassword::class)
        ->name('password.request');

    Route::get('/reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->name('logout')->middleware('auth');

Route::get('/checkout/{course:slug}', CourseCheckout::class)
    ->name('course.checkout');

Route::middleware('auth')->group(function () {
    Route::get('/payment/success/{course:slug}', PaymentSuccess::class)
        ->name('payment.success');

    Route::get('/courses', CoursesCatalog::class)
        ->name('courses.catalog');

    Route::get('/my-courses', StudentDashboard::class)
        ->name('student.dashboard');

    Route::get('/my-profile', StudentProfile::class)
        ->name('student.profile');

    /** Debe ir antes de `course.learn` para que `/exams` no se interprete como slug de lección. */
    Route::get('/learn/{course:slug}/exams', CourseExams::class)
        ->name('course.exams');

    Route::get('/learn/{course:slug}/exams/{exam}', TakeExam::class)
        ->name('course.exam.take');

    Route::get('/learn/{course:slug}/{lesson:slug?}', WatchLesson::class)
        ->name('course.learn');

    Route::get('/certificates/{course}/download', [CertificateController::class, 'download'])
        ->name('certificates.download');
});
