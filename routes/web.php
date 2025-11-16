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

//general Reports
Route::get('/reports', [ReportController::class, 'archive'])->name('reports.archive');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');


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

Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {

    
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

    // کاربران
    Route::resource('users', UserController::class);
    Route::post('users/{user}/add-certificate', [UserController::class, 'addCertificate'])->name('users.addCertificate');
    Route::get('users/search', [UserController::class, 'search'])->name('users.search');


    // دوره‌ها
    Route::resource('courses', CourseController::class);
    Route::get('courses/search', [AdminCourseController::class, 'search'])->name('courses.search');

    // برنامه‌ها
    Route::resource('programs', ProgramController::class);
    Route::get('programs/search', [ProgramController::class, 'search'])->name('programs.search');

    // بیمه
    Route::get('insurances', [InsuranceController::class, 'index'])->name('insurances.index');

    // پرداخت‌ها
    Route::get('payments', [PaymentController::class, 'AdminIndex'])->name('payments.index');
    Route::post('payments/{payment}/approve', [PaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/{payment}/reject', [PaymentController::class, 'reject'])->name('payments.reject');

    // ثبت‌نام‌ها
    Route::get('registrations', [RegistrationController::class, 'index'])->name('registrations.index');
    Route::get('registrations/{type}/{id}', [RegistrationController::class, 'show'])->name('registrations.show');
    Route::post('registrations/{registration}/approve', [RegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('registrations/{registration}/reject', [RegistrationController::class, 'reject'])->name('registrations.reject');
    Route::get('registrations/export/{type}/{id}', [RegistrationController::class, 'export'])->name('registrations.export');
    Route::get('registrations/export-pdf/{type}/{id}', [RegistrationController::class, 'exportPdf'])->name('registrations.exportPdf');


    // گزارش‌ها
    Route::get('/reports', [ReportController::class, 'Adminindex'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');
    Route::delete('/reports/{report}', [ReportController::class, 'destroy'])->name('reports.destroy');
    Route::post('/reports/{report}/approve', [ReportController::class, 'approve'])->name('reports.approve');
    Route::post('/reports/{report}/reject',  [ReportController::class, 'reject'])->name('reports.reject');

    // نظرسنجی‌ها
    Route::get('surveys/courses', [SurveyController::class, 'courseIndex'])->name('surveys.courses');
    Route::get('surveys/programs', [SurveyController::class, 'programIndex'])->name('surveys.programs');
    Route::get('surveys/stats', [SurveyController::class, 'stats'])->name('surveys.stats');


});
