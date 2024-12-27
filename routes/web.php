<?php

use App\Models\ClientHistory;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\BlockedController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\BlockTimeController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\LoginHistoryController;
use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\AppointmentScheduleController;

Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/#about', [PageController::class, 'index'])->name('about');
Route::get('/#services', [PageController::class, 'index'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [MessageController::class, 'store'])->name('message.submit');

// Schedule Appointment Routes
Route::get('/appointment', [PageController::class, 'appointment'])->name('appointment');
Route::get('/appointment/{service}/calendar', [PageController::class, 'showCalendar'])->name('appointment.calendar');
Route::get('/appointment/available-days', [PageController::class, 'getAvailableDays'])->name('appointment.available-days');
Route::get('/appointment/timeslots', [PageController::class, 'getTimeSlots'])->name('appointment.timeslots');

// Set Appointment Route
Route::post('/appointment', [AppointmentController::class, 'store'])->name('appointment.store');

Route::get('/services/tax-services', [PageController::class, 'tax_services'])->name('tax-services');
Route::get('/services/audit-representation', [PageController::class, 'audit_representation'])->name('audit-representation');
Route::get('/services/business-consulting', [PageController::class, 'business_consulting'])->name('business-consulting');
Route::get('/services/bookkeeping-and-payroll', [PageController::class, 'bookkeeping_and_payroll'])->name('bookkeeping-and-payroll');
Route::get('/services/tax-planning', [PageController::class, 'tax_planning'])->name('tax-planning');
Route::get('/services/life-insurance', [PageController::class, 'life_insurance'])->name('life-insurance');
Route::get('/services/health-insurance', [PageController::class, 'health_insurance'])->name('health-insurance');
Route::get('/dashboard', [PageController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Document Upload Related Routes
Route::get('/document-upload', [PageController::class, 'document_upload'])->name('document-upload');
Route::post('/document-upload', [UploadController::class, 'upload'])->name('document-upload.submit');
Route::post('/document-upload/check-email', [UserController::class, 'checkEmail'])->name('check.email');

Route::middleware('auth')->group(function () {
    // Agree to Terms
    Route::post('/agree-to-terms/{user}', [UserController::class, 'agreeToTerms'])->name('user.agree-to-terms');

    // Documents Related Routes
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/upload-files', [DocumentController::class, 'uploadFiles'])->name('documents.upload-files');
    Route::put('/documents/{document}/update-viewed-status', [DocumentController::class, 'updateViewedStatus'])->name('documents.update-viewed-status');
    Route::put('/documents/{document}/update-downloaded-status', [DocumentController::class, 'updateDownloadedStatus'])->name('documents.update-downloaded-status');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Tasks Related Routes
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

    Route::get('/tasks/{task}/preview', [TaskController::class, 'preview'])->name('tasks.preview');
    Route::get('/tasks/{task}/notes', [TaskController::class, 'notes'])->name('tasks.notes');
    Route::put('/tasks/{task}/update-status', [TaskController::class, 'updateStatus'])->name('tasks.update-status');

    // Appointments Related Routes
    Route::get('/appointments/{appointment}/show', [AppointmentController::class, 'show'])->name('appointments.show');
    Route::delete('/appointments/{appointment}', [AppointmentController::class, 'destroy'])->name('appointments.destroy');

    // Uploads Related Routes
    Route::get('/uploads', [UploadController::class, 'index'])->name('uploads.index');
    Route::delete('/uploads/{upload}', [UploadController::class, 'destroy'])->name('uploads.destroy');

    // PDF Related Routes
    Route::get('/save-pdf', [PDFController::class, 'saveToPDF'])->name('save.pdf');

    Route::middleware('can:accessRestrictedPages')->group(function () {
        // User Profile Related Routes
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Access Request Related Routes
        Route::get('/access-requests', [AccessRequestController::class, 'index'])->name('access-requests.index');
        Route::get('/access-requests/{user}/grant', [AccessRequestController::class, 'grantAccess'])->name('access-requests.grant');
        Route::get('/access-requests/{user}/deny', [AccessRequestController::class, 'denyAccess'])->name('access-requests.deny');

        // Client Profile Related Routes
        Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::get('/clients/{client}/show-profile', [ClientController::class, 'showProfile'])->name('clients.show-profile');
        Route::get('/clients/{client}/show-documents', [ClientController::class, 'showDocuments'])->name('clients.show-documents');
        Route::post('/clients', [ClientController::class, 'store'])->name('clients.store');
        Route::post('/clients/{client}/create-account', [ClientController::class, 'createUserAccount'])->name('clients.create-account');
        Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('/clients/{client}/profile', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

        // Tasks Related Routes
        Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit')->can('update', 'task');
        Route::put('/tasks/{task}/update', [TaskController::class, 'update'])->name('tasks.update')->can('update', 'task');
        Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->can('delete', 'task');

        // Appointments Related Routes
        Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
        Route::get('/appointments/previous', [AppointmentController::class, 'previous'])->name('appointments.previous');
        Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming'])->name('appointments.upcoming');
        Route::get('/appointments/completed', [AppointmentController::class, 'completed'])->name('appointments.completed');
        Route::get('/appointments/{appointment}/edit', [AppointmentController::class, 'edit'])->name('appointments.edit');
        Route::get('/appointments/{appointment}/reschedule', [AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::post('/appointments/reschedule/timeslots', [AppointmentController::class, 'getTimeslots'])->name('appointments.get-timeslots');
        Route::put('/appointments/{appointment}/reschedule', [AppointmentController::class, 'updateSchedule'])->name('appointments.schedule-update');
        Route::put('/appointments/{appointment}/update', [AppointmentController::class, 'update'])->name('appointments.update');

        // Messages Related Routes
        Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}/show', [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/block', [MessageController::class, 'block'])->name('messages.block');
        Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

        // Export Related Routes
        Route::get('/export/csv/client-profiles', [ExportController::class, 'exportClientProfilesCSV'])->name('export.client-profiles-csv');
        Route::get('/export/csv/appointments/{status}', [ExportController::class, 'exportAppointmentsCSV'])->name('export.appointments-csv');
        Route::get('/export/pdf/client-profiles', [ExportController::class, 'exportClientProfilesPDF'])->name('export.client-profiles-pdf');
        Route::get('/export/pdf/appointments/{status}', [ExportController::class, 'exportAppointmentsPDF'])->name('export.appointments-pdf');
    });


    Route::middleware('can:accessAdminAndManagerPages')->group(function () {
        // User Management Related Routes
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}/profile', [UserController::class, 'update_profile'])->name('users.update-profile');
        Route::put('/users/{user}/role', [UserController::class, 'update_role'])->name('users.update-role');
        Route::put('/users/{user}/password', [UserController::class, 'update_password'])->name('users.update-password');
        Route::put('/users/{user}/activate', [UserController::class, 'activate_account'])->name('users.activate');
        Route::put('/users/{user}/deactivate', [UserController::class, 'deactivate_account'])->name('users.deactivate');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        // Note Related Routes
        Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
        Route::put('/notes/{note}/update', [NoteController::class, 'update'])->name('notes.update');
        Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

        // Appointment Schedules Related Routes
        Route::get('/appointment-schedules', [AppointmentScheduleController::class, 'index'])->name('appointment-schedules.index');
        Route::get('/appointment-schedules/create', [AppointmentScheduleController::class, 'create'])->name('appointment-schedules.create');
        Route::post('/appointment-schedules', [AppointmentScheduleController::class, 'store'])->name('appointment-schedules.store');
        Route::put('/appointment-schedules/update', [AppointmentScheduleController::class, 'update'])->name('appointment-schedules.update');
        Route::get('/appointment-schedules/{schedule}/edit', [AppointmentScheduleController::class, 'edit'])->name('appointment-schedules.edit');
        Route::delete('/appointment-schedules/{schedule}', [AppointmentScheduleController::class, 'destroy'])->name('appointment-schedules.destroy');

        // Block Times Related Route
        Route::get('/block-times', [BlockTimeController::class, 'index'])->name('block-times.index');
        Route::get('/block-times/create', [BlockTimeController::class, 'create'])->name('block-times.create');
        Route::get('/block-times/{blockTime}/edit', [BlockTimeController::class, 'edit'])->name('block-times.edit');
        Route::post('/block-times', [BlockTimeController::class, 'store'])->name('block-times.store');
        Route::put('/block-times/{blockTime}/update', [BlockTimeController::class, 'update'])->name('block-times.update');
        Route::delete('/block-times/{blockTime}', [BlockTimeController::class, 'destroy'])->name('block-times.destroy');

        // Appointment Services Related Routes
        Route::get('/appointment-services', [ServiceController::class, 'index'])->name('appointment-services.index');
        Route::get('/appointment-services/create', [ServiceController::class, 'create'])->name('appointment-services.create');
        Route::post('/appointment-services', [ServiceController::class, 'store'])->name('appointment-services.store');
        Route::get('/appointment-services/{service}/edit', [ServiceController::class, 'edit'])->name('appointment-services.edit');
        Route::put('/appointment-services/{service}/update', [ServiceController::class, 'update'])->name('appointment-services.update');
        Route::delete('/appointment-services/{service}', [ServiceController::class, 'destroy'])->name('appointment-services.destroy');

        // Audit Logs Related Routes
        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('audit-logs.index');
        Route::get('/audit-logs/clear', [AuditLogController::class, 'clearAuditLogs'])->name('audit-logs.clear');

        // Login History Related Routes
        Route::get('/login-history', [LoginHistoryController::class, 'index'])->name('login-history.index');
        Route::get('/login-history/clear', [LoginHistoryController::class, 'clearLoginHistory'])->name('login-history.clear');

        // Role Related Routes
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}/profile', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

        // Permissions Related Routes
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::put('/permissions/{permission}/profile', [PermissionController::class, 'update'])->name('permissions.update');
        Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
    });

    Route::middleware('can:isClient')->group(function () {
        Route::get('/my-appointments', [AppointmentController::class, 'clientAppointments'])->name('my-appointments');
        Route::get('/appointments/book', [AppointmentController::class, 'book'])->name('appointments.client-booking');
        Route::post('/my-appointments/preview', [AppointmentController::class, 'showClientAppointment'])->name('my-appointments.preview');
        Route::post('/appointments/book', [AppointmentController::class, 'storeClientAppointment'])->name('appointments.book');
        Route::post('/appointments/schedule/timeslots', [AppointmentController::class, 'getTimeslots']);
    });

    // Blocked Related Routes
    Route::get('/blocked', [BlockedController::class, 'index'])->name('blocked.index');
    Route::delete('/blocked/{blocked}', [BlockedController::class, 'unblock'])->name('blocked.unblock');

    // Client History Related Routes
    Route::get('/client-history', [ClientHistory::class, 'index'])->name('history.index');
    Route::get('/client-history/{history}', [ClientHistory::class, 'show'])->name('history.show');

    // Search Related Routes
    Route::get('/search', [SearchController::class, 'search'])->name('search');
});

require __DIR__ . '/auth.php';
