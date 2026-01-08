<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminFiliereController;
use App\Http\Controllers\Admin\AdminUeController;
use App\Http\Controllers\Admin\AdminSalleController;
use App\Http\Controllers\Admin\AdminGroupeController;
use App\Http\Controllers\Admin\AdminSeanceController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\SeanceGeneratorController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\Teacher\SeanceController as TeacherSeanceController;
use App\Http\Controllers\Teacher\SeanceReportController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'teacher') {
        return redirect()->route('teacher.dashboard');
    } elseif ($user->role === 'delegate') {
        return redirect()->route('delegate.dashboard');
    }
    // Default dashboard or error
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'verified', 'role:admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard'); // Update this line

    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/{report}', [\App\Http\Controllers\Teacher\SeanceReportController::class, 'show'])->name('reports.show');

    Route::get('users/import', [AdminUserController::class, 'showImportForm'])->name('users.import.form');
    Route::post('users/import', [AdminUserController::class, 'importUsers'])->name('users.import');
    Route::get('users/export', [AdminUserController::class, 'exportUsers'])->name('users.export');
    Route::resource('users', AdminUserController::class);
    Route::resource('filieres', AdminFiliereController::class);
    Route::resource('ues', AdminUeController::class);
    Route::resource('salles', AdminSalleController::class);
    Route::resource('groupes', AdminGroupeController::class);

    // Advanced seance generation routes (MUST be before resource to avoid 404)
    Route::get('seances/generate/form', [SeanceGeneratorController::class, 'showForm'])->name('seances.generate.form');
    Route::get('seances/get-templates-by-filtre', [SeanceGeneratorController::class, 'getTemplatesByFiltre'])->name('seances.get-templates-by-filtre');
    Route::post('seances/generate-from-template', [SeanceGeneratorController::class, 'generateFromTemplate'])->name('seances.generate-from-template');
    Route::post('seances/generate-from-import', [SeanceGeneratorController::class, 'generateFromImport'])->name('seances.generate-from-import');
    
    Route::resource('seances', AdminSeanceController::class);
    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
});

Route::middleware(['auth', 'verified', 'role:teacher'])->name('teacher.')->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::patch('/seances/{seance}/status', [TeacherSeanceController::class, 'updateStatus'])->name('seances.updateStatus');
    Route::post('/seances/{seance}/assign-delegate', [TeacherSeanceController::class, 'assignDelegate'])->name('seances.assignDelegate');

    Route::get('/seances/{seance}/reports/create', [SeanceReportController::class, 'create'])->name('seances.reports.create');
    Route::post('/seances/{seance}/reports', [SeanceReportController::class, 'store'])->name('seances.reports.store');
    Route::get('/reports/{report}', [SeanceReportController::class, 'show'])->name('reports.show');
    Route::patch('/reports/{report}/validate', [SeanceReportController::class, 'validateReport'])->name('reports.validate');

    // Template delegates management for teachers
    Route::post('/seance-templates/{seanceTemplate}/delegates', [\App\Http\Controllers\Teacher\TemplateDelegateController::class, 'store'])->name('seance-templates.delegates.store');
    Route::delete('/seance-templates/{seanceTemplate}/delegates/{user}', [\App\Http\Controllers\Teacher\TemplateDelegateController::class, 'destroy'])->name('seance-templates.delegates.destroy');
});

use App\Http\Controllers\Delegate\DelegateController; // Use the namespaced controller

Route::middleware(['auth', 'verified', 'role:delegate'])->name('delegate.')->prefix('delegate')->group(function () {
    Route::get('/dashboard', [DelegateController::class, 'dashboard'])->name('dashboard'); // From T4
    Route::patch('/reports/{rapportSeance}/status', [DelegateController::class, 'updateReportStatus'])->name('reports.updateStatus'); // From HEAD

    // Routes for delegates to create, store, and show reports (from T4)
    // Note: These currently point to Teacher\SeanceReportController, which might need refactoring later.
    Route::get('/seances/{seance}/reports/create', [\App\Http\Controllers\Teacher\SeanceReportController::class, 'create'])->name('seances.reports.create');
    Route::post('/seances/{seance}/reports', [\App\Http\Controllers\Teacher\SeanceReportController::class, 'store'])->name('seances.reports.store');
    Route::get('/reports/{report}', [\App\Http\Controllers\Teacher\SeanceReportController::class, 'show'])->name('reports.show');
    
    // Edit/update/delete routes for delegate reports
    Route::get('/reports/{report}/edit', [DelegateController::class, 'editReport'])->name('reports.edit');
    Route::patch('/reports/{report}', [DelegateController::class, 'updateReport'])->name('reports.update');
    Route::delete('/reports/{report}', [DelegateController::class, 'deleteReport'])->name('reports.destroy');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/timetables', [TimetableController::class, 'index'])->name('timetables.index');

// Seance templates (admin only)
use App\Http\Controllers\SeanceTemplateController;
Route::middleware(['auth','verified','role:admin'])->group(function () {
    Route::delete('seance-templates/delete-group', [SeanceTemplateController::class, 'deleteGroup'])->name('seance-templates.delete-group');
    Route::resource('seance-templates', SeanceTemplateController::class);
    Route::get('seance-templates/export/show', [SeanceTemplateController::class, 'showExport'])->name('seance-templates.export.show');
    Route::post('seance-templates/export/download', [SeanceTemplateController::class, 'export'])->name('seance-templates.export');
    Route::get('seance-templates/import/show', [SeanceTemplateController::class, 'showImport'])->name('seance-templates.import');
    Route::post('seance-templates/import/store', [SeanceTemplateController::class, 'import'])->name('seance-templates.import.store');
});
