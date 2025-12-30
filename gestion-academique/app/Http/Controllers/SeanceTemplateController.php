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
        $data = $request->validate([
            'filiere_id' => 'nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'ue_id' => 'required|exists:ues,id',
            'salle_id' => 'nullable|exists:salles,id',
            'enseignant_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'comment' => 'nullable|string',
        ]);

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
        $data = $request->validate([
            'filiere_id' => 'nullable|exists:filieres,id',
            'groupe_id' => 'nullable|exists:groupes,id',
            'ue_id' => 'required|exists:ues,id',
            'salle_id' => 'nullable|exists:salles,id',
            'enseignant_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'comment' => 'nullable|string',
        ]);

        // Vérifier chevauchement (sauf le template courant)
        $this->checkForOverlap(
            $data['groupe_id'] ?? null,
            $data['filiere_id'] ?? null,
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time'],
            $seanceTemplate->id
        );

        $seanceTemplate->update($data);

        return redirect()->route('seance-templates.index')->with('success', 'Template mis à jour.');
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
     * Exporte les templates en CSV filtrés par filière/groupe
     */
    public function export(Request $request)
    {
        $query = SeanceTemplate::with(['filiere','groupe','ue','salle','enseignant']);

        if ($request->get('filiere_id')) {
            $query->where('filiere_id', $request->get('filiere_id'));
        }

        if ($request->get('groupe_id')) {
            $query->where('groupe_id', $request->get('groupe_id'));
        }

        $templates = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        $csv = Writer::createFromString('');
        
        // En-têtes
        $csv->insertOne([
            'Jour',
            'Heure Début',
            'Heure Fin',
            'UE Code',
            'UE Nom',
            'Filière',
            'Groupe',
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
                $t->ue->code,
                $t->ue->nom,
                $t->filiere->nom ?? '',
                $t->groupe->nom ?? '',
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
     * Importe les templates depuis un CSV avec filtres optionnels
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'filiere_id' => 'required|exists:filieres,id',
            'groupe_id' => 'required|exists:groupes,id',
        ]);

        $file = $request->file('file');
        $defaultFiliereId = $request->get('filiere_id');
        $defaultGroupeId = $request->get('groupe_id');

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
                    
                    $salle = $record['Salle'] ? Salle::where('numero', $record['Salle'])->first() : null;
                    
                    $enseignant = null;
                    if ($record['Enseignant']) {
                        $names = explode(' ', trim($record['Enseignant']), 2);
                        $enseignant = User::where('first_name', $names[0] ?? '')
                            ->where('last_name', $names[1] ?? '')
                            ->first();
                    }

                    // Créer ou mettre à jour
                    SeanceTemplate::updateOrCreate(
                        [
                            'filiere_id' => $filiereId,
                            'groupe_id' => $groupeId,
                            'ue_id' => $ue->id,
                            'day_of_week' => $dayOfWeek,
                            'start_time' => $record['Heure Début'],
                        ],
                        [
                            'end_time' => $record['Heure Fin'],
                            'salle_id' => $salle?->id,
                            'enseignant_id' => $enseignant?->id,
                            'comment' => $record['Commentaire'] ?? null,
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
