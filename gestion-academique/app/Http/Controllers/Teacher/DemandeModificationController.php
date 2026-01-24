<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DemandeModification;
use App\Models\Seance;
use App\Models\SeanceTemplate;
use Illuminate\Http\Request;

class DemandeModificationController extends Controller
{
    /**
     * Display the teacher's requests
     */
    public function index()
    {
        $demandes = DemandeModification::where('enseignant_id', auth()->id())
            ->with(['seance.ue', 'seance.groupe', 'seanceTemplate.ue', 'seanceTemplate.groupe', 'admin'])
            ->latest()
            ->paginate(10);

        $stats = [
            'pending' => DemandeModification::where('enseignant_id', auth()->id())->pending()->count(),
            'accepted' => DemandeModification::where('enseignant_id', auth()->id())->accepted()->count(),
            'rejected' => DemandeModification::where('enseignant_id', auth()->id())->rejected()->count(),
        ];

        return view('teacher.demandes.index', compact('demandes', 'stats'));
    }

    /**
     * Show form to create a new request
     */
    public function create()
    {
        $enseignant = auth()->user();
        
        // Get seances where this teacher is assigned
        $seances = Seance::where('enseignant_id', $enseignant->id)
            ->with(['ue', 'groupe', 'salle'])
            ->orderBy('jour', 'desc')
            ->get();

        // Get seance templates where this teacher is assigned
        $templates = SeanceTemplate::where('enseignant_id', $enseignant->id)
            ->with(['ue', 'groupe', 'salle'])
            ->orderBy('day_of_week')
            ->get();

        $typeDemandes = ['créneau', 'salle', 'enseignant', 'autre'];

        return view('teacher.demandes.create', compact('seances', 'templates', 'typeDemandes'));
    }

    /**
     * Store a new request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'seance_id' => 'nullable|integer|exists:seances,id',
            'seance_template_id' => 'nullable|integer|exists:seance_templates,id',
            'type_demande' => 'required|in:créneau,salle,enseignant,autre',
            'description' => 'required|string|min:10|max:1000',
        ]);

        // Ensure at least one seance is selected
        $seance_id = $request->input('seance_id');
        $seance_template_id = $request->input('seance_template_id');
        
        if (empty($seance_id) && empty($seance_template_id)) {
            return redirect()->back()
                ->withErrors(['seance' => 'Veuillez sélectionner une séance'])
                ->withInput();
        }

        $demande = DemandeModification::create([
            'enseignant_id' => auth()->id(),
            'seance_id' => $seance_id ?: null,
            'seance_template_id' => $seance_template_id ?: null,
            'type_demande' => $validated['type_demande'],
            'description' => $validated['description'],
            'status' => 'soumis',
        ]);

        return redirect()->route('teacher.demandes.index')
            ->with('success', 'Votre demande a été soumise avec succès');
    }

    /**
     * Show a single request
     */
    public function show(DemandeModification $demande)
    {
        // Ensure the user can only see their own requests
        if ($demande->enseignant_id !== auth()->id()) {
            abort(403);
        }

        $demande->load(['seance.ue', 'seance.groupe', 'seanceTemplate.ue', 'seanceTemplate.groupe', 'admin']);

        return view('teacher.demandes.show', compact('demande'));
    }

    /**
     * Cancel/Delete a request (only if not yet accepted or rejected)
     */
    public function destroy(DemandeModification $demande)
    {
        // Ensure the user can only delete their own requests
        if ($demande->enseignant_id !== auth()->id()) {
            abort(403);
        }

        // Only allow deletion if status is 'soumis' (pending)
        if ($demande->status !== 'soumis') {
            return redirect()->back()
                ->withErrors(['delete' => 'Vous ne pouvez annuler que les demandes en attente']);
        }

        $demande->delete();

        return redirect()->route('teacher.demandes.index')
            ->with('success', 'Votre demande a été annulée avec succès');
    }
}
