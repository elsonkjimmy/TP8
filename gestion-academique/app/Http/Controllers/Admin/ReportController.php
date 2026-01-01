<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RapportSeance;
use App\Models\Filiere;
use App\Models\Groupe;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();

        $query = RapportSeance::with(['seance.ue', 'seance.groupe', 'seance.enseignant']);

        if ($request->filled('filiere_id')) {
            $filiereId = $request->input('filiere_id');
            $query->whereHas('seance.groupe', function ($q) use ($filiereId) {
                $q->where('filiere_id', $filiereId);
            });
        }

        if ($request->filled('groupe_id')) {
            $groupeId = $request->input('groupe_id');
            $query->whereHas('seance', function ($q) use ($groupeId) {
                $q->where('groupe_id', $groupeId);
            });
        }

        $reports = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.reports.index', compact('reports', 'filieres', 'groupes'));
    }
}
