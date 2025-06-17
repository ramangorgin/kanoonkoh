<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminCourseController;
use App\Http\Controllers\Admin\AdminInsuranceController;
use App\Http\Controllers\Admin\AdminPaymentController;
use App\Http\Controllers\Admin\AdminProgramController;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminReportController;
use App\Http\Controllers\Admin\AdminSurveyController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTicketController;
use App\Http\Controllers\Admin\AdminTicketReplyController;
use App\Http\Controllers\Admin\UserProgramParticipationController;



    // صفحه اصلی پنل مدیریت
    Route::get('/', [AdminDashboardController::class, 'index'])->name('index');

    // کاربران
    Route::resource('users', AdminUserController::class);
    Route::post('users/{user}/add-certificate', [AdminUserController::class, 'addCertificate'])->name('users.addCertificate');
    Route::get('users/search', [AdminUserController::class, 'search'])->name('users.search');


    // دوره‌ها
    Route::resource('courses', AdminCourseController::class);
    Route::get('courses/search', [AdminCourseController::class, 'search'])->name('courses.search');

    // برنامه‌ها
    Route::resource('programs', AdminProgramController::class);
    Route::get('programs/search', [AdminProgramController::class, 'search'])->name('programs.search');

    // گواهی‌نامه‌ها (دوره‌های گذرانده شده کاربر)
    Route::resource('user-programs', UserProgramParticipationController::class)->only(['index', 'store', 'create']);

    // بیمه
    Route::get('insurances', [AdminInsuranceController::class, 'index'])->name('insurances.index');

    // پرداخت‌ها
    Route::get('payments', [AdminPaymentController::class, 'index'])->name('payments.index');
    Route::post('payments/{payment}/approve', [AdminPaymentController::class, 'approve'])->name('payments.approve');
    Route::post('payments/{payment}/reject', [AdminPaymentController::class, 'reject'])->name('payments.reject');

    // ثبت‌نام‌ها
    Route::get('registrations', [AdminRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('registrations/{type}/{id}', [AdminRegistrationController::class, 'show'])->name('registrations.show');
    Route::post('registrations/{registration}/approve', [AdminRegistrationController::class, 'approve'])->name('registrations.approve');
    Route::post('registrations/{registration}/reject', [AdminRegistrationController::class, 'reject'])->name('registrations.reject');
    Route::get('registrations/{type}/{id}/export', [AdminRegistrationController::class, 'export'])->name('registrations.export');
    Route::get('registrations/export-pdf/{type}/{id}', [AdminRegistrationController::class, 'exportPdf'])->name('registrations.exportPdf');


    // گزارش‌ها
    Route::resource('reports', AdminReportController::class);


    // نظرسنجی‌ها
    Route::get('surveys/courses', [AdminSurveyController::class, 'courseIndex'])->name('surveys.courses');
    Route::get('surveys/programs', [AdminSurveyController::class, 'programIndex'])->name('surveys.programs');
    Route::get('surveys/stats', [AdminSurveyController::class, 'stats'])->name('surveys.stats');


    
