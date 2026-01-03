<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\Ue; // Import Ue model
use App\Models\User;
use App\Models\RapportSeance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TeacherController extends Controller
{
    /**
     * Display the teacher's dashboard with their sessions.
     */
    public function dashboard()
    {
        $teacherId = Auth::id();

        // Week range
        $today = Carbon::now();
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);
        $sunday = $today->copy()->endOfWeek(Carbon::SUNDAY);

        // Get seances for this teacher for the week
        $seances = Seance::with(['ue', 'salle', 'groupe', 'rapportSeance', 'delegates'])
            ->where('enseignant_id', $teacherId)
            ->whereBetween('jour', [$monday->format('Y-m-d'), $sunday->format('Y-m-d')])
            ->get();

        // Build timetable grid (Mon-Sat x 3 slots)
        $days = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
        $timeSlots = [
            '08:00-11:00' => ['start' => '08:00', 'end' => '11:00'],
            '11:30-14:30' => ['start' => '11:30', 'end' => '14:30'],
            '15:00-18:00' => ['start' => '15:00', 'end' => '18:00'],
        ];

        $timetableGrid = [];
        foreach ($days as $dayKey => $dayLabel) {
            $dayDate = $monday->copy()->addDays(array_search($dayLabel, array_values($days)));
            $timetableGrid[$dayLabel] = [];
            foreach ($timeSlots as $slotKey => $slot) {
                $cells = $seances->filter(function ($s) use ($dayDate, $slot) {
                    return Carbon::parse($s->jour)->format('Y-m-d') === $dayDate->format('Y-m-d')
                        && Carbon::parse($s->heure_debut)->format('H:i') >= $slot['start']
                        && Carbon::parse($s->heure_debut)->format('H:i') < $slot['end'];
                });
                $timetableGrid[$dayLabel][$slotKey] = $cells;
            }
        }

        // Delegates (all users with role delegate)
        $delegates = User::where('role', 'delegate')->get();

        // Seance templates for this teacher (to manage template-level delegates)
        $templates = \App\Models\SeanceTemplate::with('delegates')->where('enseignant_id', $teacherId)->get();

        // UEs for progress
        $ues = Ue::where('enseignant_id', $teacherId)->get();

        // Recent reports history (last 30 days)
        $reports = RapportSeance::with('seance')->whereHas('seance', function ($q) use ($teacherId) {
            $q->where('enseignant_id', $teacherId);
        })->orderBy('created_at', 'desc')->take(50)->get();

        // Notifications for this teacher
        $notifications = \App\Models\Notification::where('destinataire_id', $teacherId)->orderBy('created_at', 'desc')->get();

        // Reports awaiting validation for this teacher
        $pendingReports = RapportSeance::with('seance')->where('status', 'submitted')->whereHas('seance', function ($q) use ($teacherId) {
            $q->where('enseignant_id', $teacherId);
        })->orderBy('created_at', 'desc')->get();

        return view('teacher.dashboard', compact('timetableGrid', 'timeSlots', 'monday', 'today', 'delegates', 'ues', 'reports', 'templates', 'notifications', 'pendingReports'));
    }

    /**
     * Update the status of a specific session.
     */
    public function updateStatus(Request $request, Seance $seance)
    {
        // Ensure the authenticated user is the assigned teacher for this session
        if ($seance->enseignant_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'status' => ['required', 'string', Rule::in(['planned', 'completed', 'cancelled'])],
        ]);

        $seance->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Statut de la séance mis à jour avec succès.');
    }
}
