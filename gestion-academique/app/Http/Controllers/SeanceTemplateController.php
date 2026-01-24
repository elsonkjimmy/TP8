<?php

namespace App\Http\Controllers;

use App\Models\SeanceTemplate;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Ue;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use Carbon\Carbon;

class SeanceTemplateController extends Controller
{
    public function index()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();

        $selectedFiliere = request()->input('filiere_id');
        $selectedGroupe = request()->input('groupe_id');

        // Filtrer les groupes selon la filière sélectionnée
        if ($selectedFiliere) {
            $groupes = Groupe::where('filiere_id', $selectedFiliere)->get();
        }

        $query = SeanceTemplate::with(['filiere','groupe','ue','salle','enseignant']);

        if ($selectedFiliere) {
            $query->where('filiere_id', $selectedFiliere);
        }

        if ($selectedGroupe) {
            $query->where('groupe_id', $selectedGroupe);
        }

        $templates = $query->get();

        // Récupérer la date du jour
        $today = Carbon::now();
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);

        // Créer la grille horaire : jour x créneau
        $days = ['Monday' => 'Lundi', 'Tuesday' => 'Mardi', 'Wednesday' => 'Mercredi', 'Thursday' => 'Jeudi', 'Friday' => 'Vendredi', 'Saturday' => 'Samedi'];
        $timeSlots = [
            '08:00-11:00' => ['start' => '08:00', 'end' => '11:00'],
            '11:30-14:30' => ['start' => '11:30', 'end' => '14:30'],
            '15:00-18:00' => ['start' => '15:00', 'end' => '18:00'],
        ];

        // Construire le tableau grille[jour][créneau] = templates
        $timetableGrid = [];
        $dayDates = [];
        foreach ([1,2,3,4,5,6] as $dayIndex) {
            $dayName = array_values($days)[$dayIndex - 1];
            $dayDate = $monday->copy()->addDays($dayIndex - 1);
            $dayDates[$dayName] = $dayDate;
            
            $timetableGrid[$dayName] = [];
            
            foreach ($timeSlots as $slotKey => $slot) {
                $dayTemplates = $templates->filter(function ($template) use ($dayIndex, $slot) {
                    return $template->day_of_week == $dayIndex && 
                           $template->start_time >= $slot['start'] && 
                           $template->start_time < $slot['end'];
                });
                $timetableGrid[$dayName][$slotKey] = $dayTemplates;
            }
        }

        return view('seance_templates.index', compact(
            'filieres',
            'groupes',
            'selectedFiliere',
            'selectedGroupe',
            'timetableGrid',
            'timeSlots',
            'today',
            'dayDates'
        ));
    }

    public function create()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $ues = Ue::all();
        $salles = Salle::all();
        $enseignants = User::where('role', 'teacher')->get();

        return view('seance_templates.create', compact('filieres','groupes','ues','salles','enseignants'));
    }

    public function store(Request $request)
    {
        // Trim time inputs
        $startTime = trim($request->input('start_time') ?? '');
        $endTime = trim($request->input('end_time') ?? '');
        
        // Validate time format (H:mm or HH:mm)
        if (!preg_match('/^[0-2]?[0-9]:[0-5][0-9]$/', $startTime) || !preg_match('/^[0-2]?[0-9]:[0-5][0-9]$/', $endTime)) {
            return back()->withErrors([
                'start_time' => 'Le format de l\'heure est invalide. Utilisez HH:mm',
                'end_time' => 'Le format de l\'heure est invalide. Utilisez HH:mm'
            ])->withInput();
        }

        // Validate that end_time > start_time
        if ($endTime <= $startTime) {
            return back()->withErrors(['end_time' => 'L\'heure fin doit être après l\'heure début.'])->withInput();
        }

        $data = $request->validate([
            'filiere_id' => 'nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'ue_id' => 'required|exists:ues,id',
            'salle_id' => 'nullable|exists:salles,id',
            'enseignant_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'semester' => 'nullable|string',
            'comment' => 'nullable|string',
            'group_divisions' => 'nullable|array',
            'group_divisions.*' => 'in:G1,G2',
        ]);
        
        // Add time values
        $data['start_time'] = $startTime;
        $data['end_time'] = $endTime;

        // Convert array of checkboxes to comma-separated string
        if (!empty($data['group_divisions'])) {
            $data['group_divisions'] = implode(',', $data['group_divisions']);
        } else {
            $data['group_divisions'] = null;
        }

        // Vérifier chevauchement
        $this->checkForOverlap(
            $data['groupe_id'] ?? null,
            $data['filiere_id'] ?? null,
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time']
        );

        SeanceTemplate::create($data);

        return redirect()->route('seance-templates.index')->with('success', 'Template ajouté.');
    }

    public function edit(SeanceTemplate $seanceTemplate)
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $ues = Ue::all();
        $salles = Salle::all();
        $enseignants = User::where('role', 'teacher')->get();

        return view('seance_templates.edit', compact('seanceTemplate','filieres','groupes','ues','salles','enseignants'));
    }

    public function update(Request $request, SeanceTemplate $seanceTemplate)
    {
        // Trim time inputs
        $startTime = trim($request->input('start_time') ?? '');
        $endTime = trim($request->input('end_time') ?? '');
        
        // If times are different from original, validate them; otherwise keep original values
        $originalStartTime = is_object($seanceTemplate->start_time) ? $seanceTemplate->start_time->format('H:i') : $seanceTemplate->start_time;
        $originalEndTime = is_object($seanceTemplate->end_time) ? $seanceTemplate->end_time->format('H:i') : $seanceTemplate->end_time;
        
        $timesModified = ($startTime !== $originalStartTime) || ($endTime !== $originalEndTime);
        
        if ($timesModified) {
            // Validate time format only if times were changed
            if (!preg_match('/^[0-2]?[0-9]:[0-5][0-9]$/', $startTime) || !preg_match('/^[0-2]?[0-9]:[0-5][0-9]$/', $endTime)) {
                return back()->withErrors([
                    'start_time' => 'Le format de l\'heure est invalide. Utilisez HH:mm',
                    'end_time' => 'Le format de l\'heure est invalide. Utilisez HH:mm'
                ])->withInput();
            }

            // Validate that end_time > start_time
            if ($endTime <= $startTime) {
                return back()->withErrors(['end_time' => 'L\'heure fin doit être après l\'heure début.'])->withInput();
            }
        } else {
            // Use original values if times weren't modified
            $startTime = $originalStartTime;
            $endTime = $originalEndTime;
        }

        $data = $request->validate([
            'filiere_id' => 'nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'ue_id' => 'required|exists:ues,id',
            'salle_id' => 'nullable|exists:salles,id',
            'enseignant_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'semester' => 'nullable|string',
            'comment' => 'nullable|string',
            'group_divisions' => 'nullable|array',
            'group_divisions.*' => 'in:G1,G2',
        ]);
        
        // Add time values
        $data['start_time'] = $startTime;
        $data['end_time'] = $endTime;

        // Convert array of checkboxes to comma-separated string
        if (!empty($data['group_divisions'])) {
            $data['group_divisions'] = implode(',', $data['group_divisions']);
        } else {
            $data['group_divisions'] = null;
        }

        $seanceTemplate->update($data);

        // Sync group_divisions to all seances with same template match (day, time, groupe, ue, enseignant)
        // Find seances that match this template's criteria and update their group_divisions
        $seances = \App\Models\Seance::where('groupe_id', $seanceTemplate->groupe_id)
            ->where('enseignant_id', $seanceTemplate->enseignant_id)
            ->where('ue_id', $seanceTemplate->ue_id)
            ->whereRaw("TIME(heure_debut) = ?", [$seanceTemplate->start_time])
            ->get();
        
        foreach ($seances as $seance) {
            // Check if seance is on the correct day of week
            $seanceDayOfWeek = Carbon::parse($seance->jour)->dayOfWeekIso;
            if ($seanceDayOfWeek == $seanceTemplate->day_of_week) {
                $seance->update(['group_divisions' => $data['group_divisions']]);
            }
        }

        return redirect()->route('seance-templates.index')->with('success', 'Template modifié.');
    }

    /**
     * Vérifie s'il y a chevauchement avec un autre template pour le même groupe/filière
     */
    private function checkForOverlap($groupeId, $filiereId, $dayOfWeek, $startTime, $endTime, $excludeId = null)
    {
        $query = SeanceTemplate::where('day_of_week', $dayOfWeek);

        // Filtrer par groupe (priorité) ou filière
        if ($groupeId) {
            $query->where('groupe_id', $groupeId);
        } elseif ($filiereId) {
            $query->where('filiere_id', $filiereId);
        } else {
            return; // Pas de validation si ni groupe ni filière
        }

        // Exclure le template actuel
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $conflicts = $query->get()->filter(function ($template) use ($startTime, $endTime) {
            // Chevauchement : start < existing_end ET end > existing_start
            return $startTime < $template->end_time && $endTime > $template->start_time;
        });

        if ($conflicts->count() > 0) {
            $conflictTimes = $conflicts->map(fn($t) => "{$t->start_time}-{$t->end_time}")->join(', ');
            abort(422, "Chevauchement détecté avec les créneaux existants: {$conflictTimes}");
        }
    }

    public function destroy(SeanceTemplate $seanceTemplate)
    {
        $seanceTemplate->delete();
        return redirect()->route('seance-templates.index')->with('success', 'Template supprimé.');
    }

    /**
     * Affiche le formulaire de filtrage pour l'export
     */
    public function showExport()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        return view('seance_templates.export_filter', compact('filieres', 'groupes'));
    }

    /**
     * Exporte les templates en CSV filtrés par filière/groupe/semestre
     */
    public function export(Request $request)
    {
        $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'groupe_id' => 'required|exists:groupes,id',
            'semester' => 'required|string',
        ]);

        $query = SeanceTemplate::with(['filiere','groupe','ue','salle','enseignant']);

        $query->where('filiere_id', $request->get('filiere_id'))
              ->where('groupe_id', $request->get('groupe_id'))
              ->where('semester', $request->get('semester'));

        $templates = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        $csv = Writer::createFromString('');
        
        // En-têtes
        $csv->insertOne([
            'Jour',
            'Heure Début',
            'Heure Fin',
            'Semestre',
            'UE Code',
            'UE Nom',
            'Filière',
            'Groupe',
            'Division',
            'Salle',
            'Enseignant',
            'Commentaire'
        ]);

        // Données
        foreach ($templates as $t) {
            $dayNames = [1=>'Lundi',2=>'Mardi',3=>'Mercredi',4=>'Jeudi',5=>'Vendredi',6=>'Samedi'];
            $csv->insertOne([
                $dayNames[$t->day_of_week],
                $t->start_time,
                $t->end_time,
                $t->semester ?? '',
                $t->ue->code,
                $t->ue->nom,
                $t->filiere->nom ?? '',
                $t->groupe->nom ?? '',
                $t->group_divisions ?? '',
                $t->salle->numero ?? '',
                ($t->enseignant->first_name ?? '') . ' ' . ($t->enseignant->last_name ?? ''),
                $t->comment ?? ''
            ]);
        }

        return response()
            ->streamDownload(function () use ($csv) {
                echo $csv->getContent();
            }, 'emploi-du-temps-' . date('Y-m-d') . '.csv', [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="emploi-du-temps-' . date('Y-m-d') . '.csv"'
            ]);
    }

    /**
     * Affiche le formulaire d'import avec options de filière/groupe
     */
    public function showImport()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        return view('seance_templates.import', compact('filieres', 'groupes'));
    }

    /**
     * Importe les templates depuis un CSV avec filtres obligatoires
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'filiere_id' => 'required|exists:filieres,id',
            'groupe_id' => 'required|exists:groupes,id',
            'semester' => 'required|string',
        ]);

        $file = $request->file('file');
        $defaultFiliereId = $request->get('filiere_id');
        $defaultGroupeId = $request->get('groupe_id');
        $defaultSemester = $request->get('semester');

        try {
            // Utiliser le chemin réel du fichier uploadé
            $csv = \League\Csv\Reader::createFromPath($file->getRealPath());
            $csv->setHeaderOffset(0);

            $dayMap = ['Lundi'=>1,'Mardi'=>2,'Mercredi'=>3,'Jeudi'=>4,'Vendredi'=>5,'Samedi'=>6];
            $imported = 0;
            $skipped = 0;
            $errors = [];

            foreach ($csv->getRecords() as $index => $record) {
                try {
                    $dayOfWeek = $dayMap[$record['Jour']] ?? null;
                    if (!$dayOfWeek) {
                        $errors[] = "Ligne " . ($index + 2) . ": jour invalide";
                        $skipped++;
                        continue;
                    }

                    // Valider les heures
                    $startTime = trim($record['Heure Début'] ?? '');
                    $endTime = trim($record['Heure Fin'] ?? '');
                    if (!$startTime || !$endTime) {
                        $errors[] = "Ligne " . ($index + 2) . ": heure début et fin obligatoires";
                        $skipped++;
                        continue;
                    }
                    
                    if (strtotime($startTime) >= strtotime($endTime)) {
                        $errors[] = "Ligne " . ($index + 2) . ": l'heure début doit être avant l'heure fin";
                        $skipped++;
                        continue;
                    }

                    // Récupérer les IDs
                    $ue = Ue::where('code', $record['UE Code'])->first();
                    if (!$ue) {
                        $errors[] = "Ligne " . ($index + 2) . ": UE code '{$record['UE Code']}' non trouvée";
                        $skipped++;
                        continue;
                    }

                    // Utiliser filtres par défaut ou valeurs du CSV
                    $filiereId = $defaultFiliereId ?: ($record['Filière'] ? Filiere::where('nom', $record['Filière'])->first()?->id : null);
                    $groupeId = $defaultGroupeId ?: ($record['Groupe'] ? Groupe::where('nom', $record['Groupe'])->first()?->id : null);
                    
                    // Vérifier que filière et groupe existent si fournis
                    if (!$filiereId) {
                        $errors[] = "Ligne " . ($index + 2) . ": filière non trouvée";
                        $skipped++;
                        continue;
                    }
                    if (!$groupeId) {
                        $errors[] = "Ligne " . ($index + 2) . ": groupe non trouvé";
                        $skipped++;
                        continue;
                    }

                    // Vérifier que le groupe appartient à la filière
                    $groupe = Groupe::find($groupeId);
                    if ($groupe && $groupe->filiere_id != $filiereId) {
                        $errors[] = "Ligne " . ($index + 2) . ": le groupe n'appartient pas à la filière sélectionnée";
                        $skipped++;
                        continue;
                    }

                    $semester = trim($record['Semestre'] ?? '') ?: $defaultSemester;
                    // Vérifier que le semestre est valide
                    if (!in_array($semester, ['S1', 'S2'])) {
                        $errors[] = "Ligne " . ($index + 2) . ": semestre invalide (doit être S1 ou S2)";
                        $skipped++;
                        continue;
                    }
                    
                    $salle = $record['Salle'] ? Salle::where('numero', $record['Salle'])->first() : null;
                    
                    $enseignant = null;
                    if ($record['Enseignant']) {
                        $names = explode(' ', trim($record['Enseignant']), 2);
                        $enseignant = User::where('first_name', $names[0] ?? '')
                            ->where('last_name', $names[1] ?? '')
                            ->first();
                        if (!$enseignant) {
                            $errors[] = "Ligne " . ($index + 2) . ": enseignant '{$record['Enseignant']}' non trouvé";
                            $skipped++;
                            continue;
                        }
                    }

                    // Traiter les divisions (Division CSV field)
                    $divisions = null;
                    if (!empty($record['Division'])) {
                        $divisionStr = trim($record['Division']);
                        // Valider que c'est G1, G2, ou G1,G2
                        $validDivisions = ['G1', 'G2', 'G1,G2', 'G2,G1'];
                        if (!in_array($divisionStr, $validDivisions)) {
                            $errors[] = "Ligne " . ($index + 2) . ": division invalide '{$divisionStr}' (doit être G1, G2 ou G1,G2)";
                            $skipped++;
                            continue;
                        }
                        $divisions = $divisionStr;
                    }

                    // Vérifier les chevauchements avec d'autres templates du même groupe
                    $conflicts = SeanceTemplate::where('groupe_id', $groupeId)
                        ->where('day_of_week', $dayOfWeek)
                        ->get()
                        ->filter(function ($template) use ($startTime, $endTime) {
                            return $startTime < $template->end_time && $endTime > $template->start_time;
                        });

                    if ($conflicts->count() > 0) {
                        $conflictTimes = $conflicts->map(fn($t) => "{$t->start_time}-{$t->end_time}")->join(', ');
                        $errors[] = "Ligne " . ($index + 2) . ": chevauchement détecté avec les créneaux existants: {$conflictTimes}";
                        $skipped++;
                        continue;
                    }

                    // Créer ou mettre à jour
                    SeanceTemplate::updateOrCreate(
                        [
                            'filiere_id' => $filiereId,
                            'groupe_id' => $groupeId,
                            'ue_id' => $ue->id,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => $startTime,
                            'semester' => $semester,
                        ],
                        [
                            'end_time' => $endTime,
                            'salle_id' => $salle?->id,
                            'enseignant_id' => $enseignant?->id,
                            'comment' => $record['Commentaire'] ?? null,
                            'semester' => $semester,
                            'group_divisions' => $divisions,
                        ]
                    );

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                    $skipped++;
                }
            }

            $message = "Importation: $imported templates ajoutés/mis à jour";
            if ($skipped > 0) {
                $message .= ", $skipped ignorés";
            }

            return redirect()->route('seance-templates.index')
                ->with('success', $message)
                ->with('errors', $errors);
        } catch (\Exception $e) {
            return redirect()->route('seance-templates.import')
                ->with('error', 'Erreur lors de la lecture du fichier: ' . $e->getMessage());
        }
    }

    public function deleteGroup(Request $request)
    {
        $filiere_id = $request->input('filiere_id');
        $groupe_id = $request->input('groupe_id');

        if (!$groupe_id) {
            return redirect()->route('seance-templates.index')->with('error', 'Un groupe doit être sélectionné.');
        }

        // Supprimer tous les templates du groupe
        SeanceTemplate::where('groupe_id', $groupe_id)->delete();

        return redirect()->route('seance-templates.index', ['filiere_id' => $filiere_id, 'groupe_id' => $groupe_id])
            ->with('success', 'L\'emploi du temps du groupe a été supprimé avec succès.');
    }
}
