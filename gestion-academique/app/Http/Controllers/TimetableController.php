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

        $selectedFiliere = $request->input('filiere_id');
        $selectedGroupe = $request->input('groupe_id');

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

        // Récupérer les séances de la semaine
        $query = Seance::with(['ue', 'salle', 'groupe', 'enseignant'])
            ->whereBetween('jour', [$monday, $sunday]);

        if ($selectedFiliere) {
            $query->whereHas('groupe', function ($q) use ($selectedFiliere) {
                $q->where('filiere_id', $selectedFiliere);
            });
        }

        if ($selectedGroupe) {
            $query->where('groupe_id', $selectedGroupe);
        }

        $seances = $query->get();

        // Créer la grille horaire : jour x créneau
        $days = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
        $timeSlots = [
            '08:00-11:00' => ['start' => '08:00', 'end' => '11:00'],
            '11:30-14:30' => ['start' => '11:30', 'end' => '14:30'],
            '15:00-18:00' => ['start' => '15:00', 'end' => '18:00'],
        ];

        // Construire le tableau grille[jour][créneau] = séances
        $timetableGrid = [];
        foreach ($days as $dayKey => $dayName) {
            $dayDate = $monday->copy()->modify($dayKey === 'Monday' ? 'monday' : ('next ' . strtolower($dayKey)));
            if ($dayKey !== 'Monday') {
                $dayDate = $monday->copy()->addDays(array_search($dayKey, array_keys($days)));
            }

            $timetableGrid[$dayName] = [];
            foreach ($timeSlots as $slotKey => $slot) {
                $daySeances = $seances->filter(function ($seance) use ($dayDate, $slot) {
                    $seanceDate = Carbon::parse($seance->jour)->format('Y-m-d');
                    $targetDate = $dayDate->format('Y-m-d');
                    $seanceStart = Carbon::parse($seance->heure_debut)->format('H:i');
                    $seanceEnd = Carbon::parse($seance->heure_fin)->format('H:i');
                    
                    return $seanceDate === $targetDate && $seanceStart >= $slot['start'] && $seanceStart < $slot['end'];
                });
                $timetableGrid[$dayName][$slotKey] = $daySeances;
            }
        }

        return view('timetables.index', compact(
            'filieres',
            'groupes',
            'selectedFiliere',
            'selectedGroupe',
            'timetableGrid',
            'timeSlots',
            'monday',
            'today'
        ));
    }
}