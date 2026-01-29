<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Desiderata;
use App\Models\SeanceTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesiderataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desideratas = Desiderata::with(['seanceTemplate.ue', 'seanceTemplate.groupe'])
            ->where('enseignant_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.desideratas.index', compact('desideratas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seance_template_id' => 'required|exists:seance_templates,id',
            'comment' => 'nullable|string|max:255',
        ]);

        // Check if already requested
        $exists = Desiderata::where('enseignant_id', Auth::id())
            ->where('seance_template_id', $validated['seance_template_id'])
            ->exists();

        if ($exists) {
            return back()->with('error', 'Vous avez déjà soumis un désirata pour ce créneau.');
        }

        Desiderata::create([
            'enseignant_id' => Auth::id(),
            'seance_template_id' => $validated['seance_template_id'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', 'Votre désirata a été soumis avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Desiderata $desiderata)
    {
        // Ensure own desiderata
        if ($desiderata->enseignant_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deleting pending
        if ($desiderata->status !== 'pending') {
            return back()->with('error', 'Impossible de supprimer un désirata traité.');
        }

        $desiderata->delete();

        return back()->with('success', 'Désirata annulé.');
    }
}
