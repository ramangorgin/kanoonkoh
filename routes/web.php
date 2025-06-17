<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\ProgramRegistrationController;

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseRegistrationController;


use App\Http\Controllers\ReportPublicController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\ProfileController;
use App\Http\Controllers\Dashboard\InsuranceController;
use App\Http\Controllers\Dashboard\PaymentController;
use App\Http\Controllers\Dashboard\TicketController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\ReportController;
use App\Http\Controllers\Dashboard\TicketReplyController;




Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/conditions', 'conditions')->name('conditions');


//general Programs
Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');
Route::middleware('auth')->post('/programs/{program}/register', [ProgramRegistrationController::class, 'store'])->name('programs.register');


//general Courses
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::middleware('auth')->post('/courses/{course}/register', [CourseRegistrationController::class, 'store'])->name('courses.register');

//general Reports
Route::get('/reports', [ReportPublicController::class, 'index'])->name('reports.index');
Route::get('/reports/{id}', [ReportPublicController::class, 'show'])->name('reports.show');

//User Authentication routes:
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


//User Dashboard routes:
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/courses', [DashboardController::class, 'courses'])->name('courses');
    Route::get('/programs', [DashboardController::class, 'programs'])->name('programs');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');

    Route::get('/insurance', [InsuranceController::class, 'show'])->name('insurance');
    Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');

    Route::get('/payments', [PaymentController::class, 'index'])->name('payments');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');

    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::get('/reports/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::get('/reports/show', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports/create', [ReportController::class, 'store'])->name('reports.store');

});


Route::middleware(['web', 'auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(base_path('routes/admin.php'));
