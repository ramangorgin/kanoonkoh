<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserCoursesController;
use App\Http\Controllers\EducationalHistoryController;

use App\Http\Controllers\RegistrationController;

use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminPaymentController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;


use App\Http\Controllers\AuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/conditions', 'conditions')->name('conditions');

// Auth: Login & Register
// ==========================
Route::get('/auth/phone', [AuthController::class, 'showPhoneForm'])->name('auth.phone');
Route::post('/auth/phone', [AuthController::class, 'requestOtp'])->name('auth.requestOtp');

Route::get('/auth/verify', [AuthController::class, 'showVerifyForm'])->name('auth.verifyForm');
Route::post('/auth/verify', [AuthController::class, 'verifyOtp'])->name('auth.verifyOtp');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// ==========================

// Sign-Up Wizard
// ==========================
Route::get('/auth/register/step1', [AuthController::class, 'showRegisterStep1'])->name('auth.register.step1');
Route::post('/auth/register/step1', [AuthController::class, 'storeRegisterStep1'])->name('auth.register.storeStep1');

Route::get('/auth/register/step2', [AuthController::class, 'showRegisterStep2'])->name('auth.register.step2');
Route::post('/auth/register/step2', [AuthController::class, 'storeRegisterStep2'])->name('auth.register.storeStep2');

Route::get('/auth/register/step3', [AuthController::class, 'showRegisterStep3'])->name('auth.register.step3');
Route::post('/auth/register/step3', [AuthController::class, 'storeRegisterStep3'])->name('auth.register.storeStep3');

Route::get('/auth/register/complete', [AuthController::class, 'registerComplete'])->name('auth.register.complete');
// ==========================

//general Programs
Route::get('/programs', [ProgramController::class, 'archive'])->name('programs.archive');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');


//general Courses
Route::get('/courses', [CourseController::class, 'archive'])->name('courses.archive');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');



//User Dashboard routes:
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {

    Route::get('/', [UserDashboardController::class, 'index'])->name('index');

    Route::get('/educational-histories', [EducationalHistoryController::class, 'index'])->name('educationalHistory.index');
    Route::post('/educational-histories', [EducationalHistoryController::class, 'store'])->name('educationalHistory.store');
    Route::put('/educational-histories/{id}', [EducationalHistoryController::class, 'update'])->name('educationalHistory.update');
    Route::delete('/educational-histories/{id}', [EducationalHistoryController::class, 'destroy'])->name('educationalHistory.destroy');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
    Route::put('/profile/{user}', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/medical-record', [MedicalRecordController::class, 'show'])->name('medicalRecord.show');
    Route::put('/medical-record', [MedicalRecordController::class, 'update'])->name('medicalRecord.update');

    Route::get('/my-payments', [PaymentController::class, 'UserIndex'])->name('payments.index');
    Route::post('/my-payments', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

});

Route::get('/api/programs/list', [PaymentController::class, 'getPrograms']);
Route::get('/api/courses/list', [PaymentController::class, 'getCourses']);

// Registratoins for Users:

//get the form of registrations
Route::get('registrations/program/{program}', [RegistrationController::class, 'createProgram'])->name('registrations.program.create');
Route::get('registrations/course/{course}', [RegistrationController::class, 'createCourse'])->name('registrations.course.create');

// post the form of regsitrations
Route::post('/registrations/program/{program}', [RegistrationController::class, 'ProgramStore'])->name('registration.program.store');
Route::post('/registrations/course/{course}', [RegistrationController::class, 'CourseStore'])->name('registration.course.store');



//Admin Dashboard routes:

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/memberships/pending', [AdminUserController::class, 'pendingMemberships'])->name('admin.memberships.pending');
    Route::post('/users/{id}/approve', [AdminUserController::class, 'approveMembership'])->name('admin.users.approve');
    Route::post('/users/{id}/reject', [AdminUserController::class, 'rejectMembership'])->name('admin.users.reject');

    Route::get('/users/export', [AdminUserController::class, 'export'])->name('admin.users.export');

    Route::get('/payments', [AdminPaymentController::class, 'index'])->name('admin.payments.index');
    Route::get('/payments/{id}', [AdminPaymentController::class, 'show'])->name('admin.payments.show');
    Route::post('/payments/{id}/approve', [AdminPaymentController::class, 'approve'])->name('admin.payments.approve');
    Route::post('/payments/{id}/reject', [AdminPaymentController::class, 'reject'])->name('admin.payments.reject');
    Route::get('/payments/export', [AdminPaymentController::class, 'export'])->name('admin.payments.export');
});

