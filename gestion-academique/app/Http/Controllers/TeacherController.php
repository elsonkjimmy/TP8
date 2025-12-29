<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Models\Ue; // Import Ue model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherController extends Controller
{
    /**
     * Display the teacher's dashboard with their sessions.
     */
    public function dashboard()
    {
        $teacherId = Auth::id();
        $seances = Seance::where('enseignant_id', $teacherId)
                         ->with(['ue', 'salle', 'groupe'])
                         ->orderBy('jour')
                         ->orderBy('heure_debut')
                         ->get();

        $ues = Ue::where('enseignant_id', $teacherId)
                 ->with('seances') // Eager load seances to calculate progress
                 ->get();

        return view('teacher.dashboard', compact('seances', 'ues'));
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
