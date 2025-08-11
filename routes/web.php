<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\ProgramController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReportController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingsController;

use App\Http\Controllers\AdminDashboardController;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\UserController;




Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/conditions', 'conditions')->name('conditions');


//general Programs
Route::get('/programs', [ProgramController::class, 'archive'])->name('programs.archive');
Route::get('/programs/{program}', [ProgramController::class, 'show'])->name('programs.show');


//general Courses
Route::get('/courses', [CourseController::class, 'archive'])->name('courses.archive');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');

//general Reports
Route::get('/reports', [ReportController::class, 'archive'])->name('reports.archive');
Route::get('/reports/{id}', [ReportController::class, 'show'])->name('reports.show');

//User Authentication routes:
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/sitemap.xml', [App\Http\Controllers\SitemapController::class, 'index']);


Route::get('/survey/course', [SurveyController::class, 'courseForm'])->name('surveys.course.form');
Route::get('/survey/program', [SurveyController::class, 'programForm'])->name('surveys.program.form');
Route::post('/survey/course', [SurveyController::class, 'submitCourse'])->name('surveys.course.submit');
Route::post('/survey/program', [SurveyController::class, 'submitProgram'])->name('surveys.program.submit');


//User Dashboard routes:
Route::prefix('dashboard')->name('dashboard.')->middleware('auth')->group(function () {

    Route::get('/', [UserDashboardController::class, 'index'])->name('index');

    Route::get('/courses', [UserDashboardController::class, 'courses'])->name('courses');

    Route::get('/programs', [UserDashboardController::class, 'programs'])->name('programs');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');

    Route::get('/insurance', [InsuranceController::class, 'show'])->name('insurance');
    Route::post('/insurance', [InsuranceController::class, 'store'])->name('insurance.store');

    Route::get('/payments', [PaymentController::class, 'UserIndex'])->name('payments');
    Route::post('/payment', [PaymentController::class, 'store'])->name('payment.store');


    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'updatePassword'])->name('settings.updatePassword');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::get('/reports/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::get('/reports/update', [ReportController::class, 'update'])->name('reports.update');
    Route::get('/reports/show', [ReportController::class, 'show'])->name('reports.show');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');

});

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
    Route::resource('reports', ReportController::class);


    // نظرسنجی‌ها
    Route::get('surveys/courses', [SurveyController::class, 'courseIndex'])->name('surveys.courses');
    Route::get('surveys/programs', [SurveyController::class, 'programIndex'])->name('surveys.programs');
    Route::get('surveys/stats', [SurveyController::class, 'stats'])->name('surveys.stats');


});
