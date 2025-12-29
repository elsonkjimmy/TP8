<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $teachers = User::where('role', 'teacher')->get();

        $selectedFiliere = $request->input('filiere_id');
        $selectedGroupe = $request->input('groupe_id');
        $selectedTeacher = $request->input('enseignant_id');
        $selectedDate = $request->input('date', Carbon::now()->format('Y-m-d'));

        $query = Seance::with(['ue', 'salle', 'groupe', 'enseignant'])
            ->whereDate('jour', $selectedDate);

        if ($selectedFiliere) {
            $query->whereHas('groupe', function ($q) use ($selectedFiliere) {
                $q->where('filiere_id', $selectedFiliere);
            });
        }

        if ($selectedGroupe) {
            $query->where('groupe_id', $selectedGroupe);
        }

        if ($selectedTeacher) {
            $query->where('enseignant_id', $selectedTeacher);
        }

        $seances = $query->orderBy('heure_debut')->get();

        // Group sessions by time for the timetable grid
        $timetableGrid = [];
        $hours = ["08:00", "09:00", "10:00", "11:00", "12:00", "13:00", "14:00", "15:00", "16:00", "17:00", "18:00"]; // Example hours

        foreach ($hours as $hour) {
            $timetableGrid[$hour] = $seances->filter(function ($seance) use ($hour) {
                return Carbon::parse($seance->heure_debut)->format('H:i') <= $hour && Carbon::parse($seance->heure_fin)->format('H:i') > $hour;
            });
        }


        return view('timetables.index', compact(
            'filieres',
            'groupes',
            'teachers',
            'seances',
            'selectedFiliere',
            'selectedGroupe',
            'selectedTeacher',
            'selectedDate',
            'timetableGrid',
            'hours'
        ));
    }
}