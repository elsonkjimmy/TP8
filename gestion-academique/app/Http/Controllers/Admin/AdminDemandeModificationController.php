<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandeModification;
use Illuminate\Http\Request;

class AdminDemandeModificationController extends Controller
{
    /**
     * Display all requests
     */
    public function index(Request $request)
    {
        $query = DemandeModification::with(['enseignant', 'seance.ue', 'seance.groupe', 'seanceTemplate.ue', 'seanceTemplate.groupe', 'admin']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type_demande', $request->type);
        }

        // Filter by enseignant
        if ($request->has('enseignant_id') && $request->enseignant_id !== '') {
            $query->where('enseignant_id', $request->enseignant_id);
        }

        $demandes = $query->latest()->paginate(15);

        $stats = [
            'total' => DemandeModification::count(),
            'pending' => DemandeModification::pending()->count(),
            'accepted' => DemandeModification::accepted()->count(),
            'rejected' => DemandeModification::rejected()->count(),
        ];

        return view('admin.demandes.index', compact('demandes', 'stats'));
    }

    /**
     * Show a single request
     */
    public function show(DemandeModification $demande)
    {
        // Ensure proper relationship loading
        $demande = DemandeModification::with([
            'enseignant',
            'seance' => fn($q) => $q->with(['ue', 'groupe', 'salle']),
            'seanceTemplate' => fn($q) => $q->with(['ue', 'groupe', 'salle']),
            'admin'
        ])->findOrFail($demande->id);

        return view('admin.demandes.show', compact('demande'));
    }

    /**
     * Accept a request
     */
    public function accept(Request $request, DemandeModification $demande)
    {
        $validated = $request->validate([
            'admin_response' => 'nullable|string|max:500',
        ]);

        $demande->update([
            'status' => 'accepté',
            'admin_id' => auth()->id(),
            'admin_response' => $validated['admin_response'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Demande acceptée');
    }

    /**
     * Reject a request
     */
    public function reject(Request $request, DemandeModification $demande)
    {
        $validated = $request->validate([
            'admin_response' => 'required|string|max:500',
        ]);

        $demande->update([
            'status' => 'rejeté',
            'admin_id' => auth()->id(),
            'admin_response' => $validated['admin_response'],
        ]);

        return redirect()->back()->with('success', 'Demande rejetée');
    }
}
