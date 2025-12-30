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
use App\Http\Controllers\Admin\AdminDashboardController; // Add this line
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherRapportSeanceController;

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

    Route::get('users/import', [AdminUserController::class, 'showImportForm'])->name('users.import.form');
    Route::post('users/import', [AdminUserController::class, 'importUsers'])->name('users.import');
    Route::resource('users', AdminUserController::class);
    Route::resource('filieres', AdminFiliereController::class);
    Route::resource('ues', AdminUeController::class);
    Route::resource('salles', AdminSalleController::class);
    Route::resource('groupes', AdminGroupeController::class);
    Route::resource('seances', AdminSeanceController::class);
    Route::get('notifications', [AdminNotificationController::class, 'index'])->name('notifications.index');
    Route::get('notifications/create', [AdminNotificationController::class, 'create'])->name('notifications.create');
    Route::post('notifications', [AdminNotificationController::class, 'store'])->name('notifications.store');
});

Route::middleware(['auth', 'verified', 'role:teacher'])->name('teacher.')->prefix('teacher')->group(function () {
    Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
    Route::patch('/seances/{seance}/status', [TeacherController::class, 'updateStatus'])->name('seances.updateStatus');

    Route::get('/seances/{seance}/reports/create', [TeacherRapportSeanceController::class, 'create'])->name('seances.reports.create');
    Route::post('/seances/{seance}/reports', [TeacherRapportSeanceController::class, 'store'])->name('seances.reports.store');
    Route::get('/reports/{rapportSeance}', [TeacherRapportSeanceController::class, 'show'])->name('reports.show');
});

Route::middleware(['auth', 'verified', 'role:delegate'])->name('delegate.')->prefix('delegate')->group(function () {
    Route::get('/dashboard', function () {
        return view('delegate.dashboard');
    })->name('dashboard');
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
    Route::resource('seance-templates', SeanceTemplateController::class);
    Route::get('seance-templates/export/show', [SeanceTemplateController::class, 'showExport'])->name('seance-templates.export.show');
    Route::post('seance-templates/export/download', [SeanceTemplateController::class, 'export'])->name('seance-templates.export');
    Route::get('seance-templates/import/show', [SeanceTemplateController::class, 'showImport'])->name('seance-templates.import');
    Route::post('seance-templates/import/store', [SeanceTemplateController::class, 'import'])->name('seance-templates.import.store');
});
