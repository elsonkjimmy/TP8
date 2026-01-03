<?php

namespace App\Http\Controllers;

use App\Models\RapportSeance;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherRapportSeanceController extends Controller
{
    /**
     * Show the form for creating a new session report.
     */
    public function create(Seance $seance)
    {
        // Ensure the authenticated user is the assigned teacher for this session
        if ($seance->enseignant_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Check if a report already exists for this session
        if (RapportSeance::where('seance_id', $seance->id)->exists()) {
            return redirect()->route('teacher.dashboard')->with('error', 'Un rapport existe déjà pour cette séance.');
        }

        $seance->load('ue.chapters');
        return view('teacher.reports.create', compact('seance'));
    }

    /**
     * Store a newly created session report in storage.
     */
    public function store(Request $request, Seance $seance)
    {
        // Ensure the authenticated user is the assigned teacher for this session
        if ($seance->enseignant_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'contenu' => ['required', 'string'],
            'statut' => ['required', 'string', Rule::in(['pending', 'approved', 'rejected'])],
            'chapter_id' => ['nullable','exists:chapters,id'],
        ]);

        RapportSeance::create([
            'seance_id' => $seance->id,
            'enseignant_id' => Auth::id(),
            'délégué_id' => null,
            'contenu' => $validated['contenu'],
            'statut' => $validated['statut'],
            'chapter_id' => $validated['chapter_id'] ?? null,
        ]);

        return redirect()->route('teacher.dashboard')->with('success', 'Rapport de séance créé avec succès.');
    }

    /**
     * Display the specified session report.
     */
    public function show(RapportSeance $rapportSeance)
    {
        // Ensure the authenticated user has permission to view this report
        $user = Auth::user();
        $seance = $rapportSeance->seance;

        if ($user->role === 'admin' ||
            ($user->role === 'teacher' && $seance->enseignant_id === $user->id) ||
            ($user->role === 'delegate' && $seance->groupe->delegue_id === $user->id) // Assuming delegate_id on Groupe
        ) {
            return view('teacher.reports.show', compact('rapportSeance'));
        }

        abort(403, 'Unauthorized action.');
    }
}