<?php

namespace App\Http\Controllers;

use App\Models\Groupe;
use App\Models\RapportSeance;
use App\Models\Seance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DelegateController extends Controller
{
    /**
     * Display the delegate's dashboard.
     */
    public function index()
    {
        $delegate = Auth::user();
        $groupe = Groupe::where('delegue_id', $delegate->id)->first(); // Assuming a delegate is linked to a group

        $seances = collect();
        $reportsToValidate = collect();

        if ($groupe) {
            $seances = Seance::where('groupe_id', $groupe->id)
                             ->with(['ue', 'salle', 'enseignant'])
                             ->orderBy('jour', 'desc')
                             ->orderBy('heure_debut', 'desc')
                             ->get();

            $reportsToValidate = RapportSeance::whereHas('seance', function ($query) use ($groupe) {
                                    $query->where('groupe_id', $groupe->id);
                                })
                                ->where('statut', 'pending') // Reports awaiting delegate validation
                                ->with(['seance.ue', 'seance.enseignant'])
                                ->get();
        }

        return view('delegate.dashboard', compact('delegate', 'groupe', 'seances', 'reportsToValidate'));
    }

    /**
     * Update the status of a specific session report.
     */
    public function updateReportStatus(Request $request, RapportSeance $rapportSeance)
    {
        $delegate = Auth::user();
        $groupe = Groupe::where('delegue_id', $delegate->id)->first();

        // Ensure the authenticated user is a delegate and belongs to the group of the session
        if (!$groupe || $rapportSeance->seance->groupe_id !== $groupe->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'statut' => ['required', 'string', Rule::in(['approved', 'rejected'])],
        ]);

        $rapportSeance->update([
            'statut' => $validated['statut'],
            'délégué_id' => $delegate->id, // Assign the delegate who validated it
        ]);

        return redirect()->back()->with('success', 'Statut du rapport mis à jour avec succès.');
    }
}
