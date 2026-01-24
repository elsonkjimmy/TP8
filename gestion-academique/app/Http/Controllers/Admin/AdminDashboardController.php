<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Notification;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\Ue;
use App\Models\User;
use App\Services\ClasseCompleteService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // User statistics
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalDelegates = User::where('role', 'delegate')->count();

        // Entity statistics
        $totalFilieres = Filiere::count();
        $totalUes = Ue::count();
        $totalSalles = Salle::count();
        $totalGroupes = Groupe::count();

        // Session statistics
        $totalSeances = Seance::count();
        $completedSeances = Seance::where('status', 'completed')->count();
        $pendingSeances = Seance::where('status', 'planned')->count();
        $cancelledSeances = Seance::where('status', 'cancelled')->count();

        // Recent notifications
        $recentNotifications = Notification::with(['expediteur', 'destinataireUser'])
                                            ->latest()
                                            ->take(5)
                                            ->get();

        // Overall UE progress (average)
        $ues = Ue::with('seances')->get();
        $overallUeProgress = 0;
        if ($ues->count() > 0) {
            $totalProgress = 0;
            foreach ($ues as $ue) {
                $totalProgress += $ue->progress;
            }
            $overallUeProgress = round($totalProgress / $ues->count(), 2);
        }

        // Complete classes alerts
        $anneeActuelle = date('Y');
        $completeClasses = [];
        try {
            $completeClasses = ClasseCompleteService::getCompleteClasses($anneeActuelle);
        } catch (\Exception $e) {
            // Silently fail if there's an issue with the service
            \Log::warning('ClasseCompleteService error: ' . $e->getMessage());
            $completeClasses = [];
        }


        return view('admin.dashboard', compact(
            'totalUsers',
            'totalAdmins',
            'totalTeachers',
            'totalDelegates',
            'totalFilieres',
            'totalUes',
            'totalSalles',
            'totalGroupes',
            'totalSeances',
            'completedSeances',
            'pendingSeances',
            'cancelledSeances',
            'recentNotifications',
            'overallUeProgress',
            'completeClasses'
        ));
    }
}