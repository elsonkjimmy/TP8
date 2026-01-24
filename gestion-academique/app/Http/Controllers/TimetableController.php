<?php

namespace App\Http\Controllers;

use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Seance;
use App\Models\SeanceTemplate;
use App\Models\User;
use App\Models\Salle;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TimetableController extends Controller
{
    public function index(Request $request)
    {
        $filieres = Filiere::all();
        $enseignants = User::where('role', 'teacher')->orderBy('first_name')->get();
        $salles = Salle::orderBy('numero')->get();

        $selectedFiliere = $request->input('filiere_id');
        $selectedGroupe = $request->input('groupe_id');
        $selectedEnseignant = $request->input('enseignant_id');
        $selectedSalle = $request->input('salle_id');

        // Filtrer les groupes selon la filière sélectionnée
        if ($selectedFiliere) {
            $groupes = Groupe::where('filiere_id', $selectedFiliere)->get();
        } else {
            $groupes = Groupe::all();
        }

        // Récupérer la semaine actuelle (lundi à dimanche)
        $today = Carbon::now();
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);
        $sunday = $today->copy()->endOfWeek(Carbon::SUNDAY);

        // Récupérer les séances datées de la semaine
        $querySeances = Seance::with(['ue', 'salle', 'groupe', 'enseignant'])
            ->whereBetween('jour', [$monday, $sunday]);

        if ($selectedFiliere) {
            $querySeances->whereHas('groupe', function ($q) use ($selectedFiliere) {
                $q->where('filiere_id', $selectedFiliere);
            });
        }

        if ($selectedGroupe) {
            $querySeances->where('groupe_id', $selectedGroupe);
        }

        if ($selectedEnseignant) {
            $querySeances->where('enseignant_id', $selectedEnseignant);
        }

        if ($selectedSalle) {
            $querySeances->where('salle_id', $selectedSalle);
        }

        $seances = $querySeances->get();

        // Récupérer les templates d'emploi du temps
        $queryTemplates = SeanceTemplate::with(['ue', 'salle', 'groupe', 'filiere', 'enseignant']);

        if ($selectedFiliere) {
            $queryTemplates->where('filiere_id', $selectedFiliere);
        }

        if ($selectedGroupe) {
            $queryTemplates->where('groupe_id', $selectedGroupe);
        }

        if ($selectedEnseignant) {
            $queryTemplates->where('enseignant_id', $selectedEnseignant);
        }

        if ($selectedSalle) {
            $queryTemplates->where('salle_id', $selectedSalle);
        }

        $templates = $queryTemplates->get();

        // Créer la grille horaire : jour x créneau
        $days = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
        $timeSlots = [
            '08:00-11:00' => ['start' => '08:00', 'end' => '11:00'],
            '11:30-14:30' => ['start' => '11:30', 'end' => '14:30'],
            '15:00-18:00' => ['start' => '15:00', 'end' => '18:00'],
        ];

        // Construire le tableau grille[jour][créneau] = séances (combinaison de Seance + SeanceTemplate)
        $timetableGrid = [];
        foreach ($days as $dayKey => $dayName) {
            $dayDate = $monday->copy()->modify($dayKey === 'Monday' ? 'monday' : ('next ' . strtolower($dayKey)));
            if ($dayKey !== 'Monday') {
                $dayDate = $monday->copy()->addDays(array_search($dayKey, array_keys($days)));
            }

            $dayOfWeek = $dayDate->dayOfWeek == 0 ? 7 : $dayDate->dayOfWeek; // 1=Lun, ..., 6=Sam (ISO)
            
            $timetableGrid[$dayName] = [];
            foreach ($timeSlots as $slotKey => $slot) {
                // Récupérer les séances datées pour ce créneau
                $daySeances = $seances->filter(function ($seance) use ($dayDate, $slot) {
                    $seanceDate = Carbon::parse($seance->jour)->format('Y-m-d');
                    $targetDate = $dayDate->format('Y-m-d');
                    $seanceStart = Carbon::parse($seance->heure_debut)->format('H:i');
                    
                    return $seanceDate === $targetDate && $seanceStart >= $slot['start'] && $seanceStart < $slot['end'];
                });

                // Récupérer les templates pour ce créneau (si pas de séances datées)
                $dayTemplates = [];
                if ($daySeances->isEmpty()) {
                    $dayTemplates = $templates->filter(function ($template) use ($dayOfWeek, $slot) {
                        return $template->day_of_week == $dayOfWeek && 
                               $template->start_time >= $slot['start'] && 
                               $template->start_time < $slot['end'];
                    });
                }

                // Combiner séances datées + templates (priorité aux séances datées)
                $timetableGrid[$dayName][$slotKey] = $daySeances->isNotEmpty() ? $daySeances : $dayTemplates;
            }
        }

        return view('timetables.index', compact(
            'filieres',
            'groupes',
            'enseignants',
            'salles',
            'selectedFiliere',
            'selectedGroupe',
            'selectedEnseignant',
            'selectedSalle',
            'timetableGrid',
            'timeSlots',
            'monday',
            'today'
        ));
    }
}