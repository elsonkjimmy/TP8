<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\GroupeEffectif;
use Illuminate\Http\Request;

class GroupeEffectifController extends Controller
{
    /**
     * Display a listing of effectifs
     */
    public function index()
    {
        $groupes = Groupe::with(['effectifs', 'filiere'])->get();
        $anneeActuelle = date('Y');
        $annees = range($anneeActuelle - 2, $anneeActuelle + 2);
        $semesters = ['S1', 'S2'];

        return view('admin.groupe-effectifs.index', compact('groupes', 'annees', 'semesters', 'anneeActuelle'));
    }

    /**
     * Store or update effectif
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'groupe_id' => 'required|exists:groupes,id',
            'annee' => 'required|integer|min:2020|max:2100',
            'semestre' => 'required|in:S1,S2',
            'effectif' => 'required|integer|min:1|max:500',
        ]);

        GroupeEffectif::updateOrCreate(
            [
                'groupe_id' => $validated['groupe_id'],
                'annee' => $validated['annee'],
                'semestre' => $validated['semestre'],
            ],
            [
                'effectif' => $validated['effectif'],
            ]
        );

        return redirect()->route('admin.groupe-effectifs.index')
            ->with('success', 'Effectif mis à jour avec succès.');
    }

    /**
     * Delete an effectif
     */
    public function destroy(GroupeEffectif $groupeEffectif)
    {
        $groupeEffectif->delete();

        return redirect()->route('admin.groupe-effectifs.index')
            ->with('success', 'Effectif supprimé avec succès.');
    }
}
