<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeanceTemplate;
use App\Models\Seance;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class SeanceGeneratorController extends Controller
{
    /**
     * Show the advanced generation form (with 2 options).
     */
    public function showForm()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $semesters = ['S1', 'S2'];
        
        // Get all templates for debug dropdown
        $allTemplates = SeanceTemplate::with(['ue', 'filiere', 'groupe'])
            ->orderBy('filiere_id')
            ->orderBy('groupe_id')
            ->orderBy('semester')
            ->get()
            ->map(function ($t) {
                $dayNames = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];
                $filiereName = $t->filiere?->nom ?? 'N/A';
                $groupeName = $t->groupe?->nom ?? 'N/A';
                $ueName = $t->ue?->nom ?? 'N/A';
                $ueCode = $t->ue?->code ?? 'N/A';
                $dayName = $dayNames[$t->day_of_week] ?? 'N/A';
                
                return [
                    'id' => $t->id,
                    'display' => "{$filiereName}-{$groupeName}-{$t->semester} ({$ueCode} {$dayName} {$t->start_time}-{$t->end_time})",
                    'filiere_id' => $t->filiere_id,
                    'groupe_id' => $t->groupe_id,
                    'semester' => $t->semester,
                    'ue_code' => $ueCode,
                    'ue_nom' => $ueName,
                    'heure_debut' => $t->start_time,
                    'heure_fin' => $t->end_time,
                    'jour' => $dayName,
                ];
            });

        return view('admin.seances.generate-advanced', compact('filieres', 'groupes', 'semesters', 'allTemplates'));
    }

    /**
     * Get templates for selected filiere/groupe (AJAX).
     */
    public function getTemplatesByFiltre(Request $request)
    {
        $filiereId = $request->get('filiere_id');
        $groupeId = $request->get('groupe_id');

        Log::info('getTemplatesByFiltre called', ['filiere_id' => $filiereId, 'groupe_id' => $groupeId, 'user_id' => auth()->id()]);

        try {
            $query = SeanceTemplate::query();
            if ($filiereId) {
                $query->where('filiere_id', $filiereId);
            }
            if ($groupeId) {
                $query->where('groupe_id', $groupeId);
            }

            $templates = $query->with(['ue', 'filiere', 'groupe'])->get();
            
            $dayNames = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'];

            $result = $templates->map(function ($t) use ($dayNames) {
                $ueCode = $t->ue?->code ?? 'N/A';
                $ueNom = $t->ue?->nom ?? 'N/A';
                $semester = $t->semester ?? 'N/A';
                $dayName = $dayNames[$t->day_of_week] ?? 'N/A';
                $filiereName = $t->filiere?->nom ?? 'N/A';
                $groupeName = $t->groupe?->nom ?? 'N/A';
                
                return [
                    'id' => $t->id,
                    'label' => "{$ueNom} - {$t->start_time}-{$t->end_time} ({$semester})",
                    'display_name' => "{$filiereName}-{$groupeName}-{$semester}",
                    'semester' => $t->semester,
                    'ue_code' => $ueCode,
                    'ue_nom' => $ueNom,
                    'heure_debut' => $t->start_time,
                    'heure_fin' => $t->end_time,
                    'jours_semaine' => $dayName,
                ];
            });

            Log::info('getTemplatesByFiltre success', ['count' => $result->count()]);
            return response()->json($result);
        } catch (\Throwable $e) {
            Log::error('Error in getTemplatesByFiltre', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json(['error' => 'Erreur serveur lors de la récupération des templates'], 500);
        }
    }

    /**
     * Generate seances (Option A: from existing template).
     */
    public function generateFromTemplate(Request $request)
    {
        Log::info('generateFromTemplate called', $request->all());
        $validated = $request->validate([
            'filiere_id' => 'required|exists:filieres,id',
            'groupe_id' => 'required|exists:groupes,id',
            'target_semester' => 'required|in:S1,S2',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        // Find unique template
        $templates = SeanceTemplate::where('filiere_id', $validated['filiere_id'])
            ->where('groupe_id', $validated['groupe_id'])
            ->where('semester', $validated['target_semester'])
            ->get();

        if ($templates->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Aucun template trouvé pour cette filière, groupe et semestre.')
                ->withInput();
        }

        $targetSemester = $validated['target_semester'];

        // Alert if any template semester differs from target
        $semesterAlert = null;
        foreach ($templates as $t) {
            if ($t->semester && $t->semester !== $targetSemester) {
                $semesterAlert = "Certains templates utilisent un semestre différent. Les séances générées seront en {$targetSemester}.";
                break;
            }
        }

        // User must confirm the alert
        if ($semesterAlert && !$request->get('confirm')) {
            Log::info('generateFromTemplate requires confirmation', ['alert' => $semesterAlert]);
            return redirect()->back()
                ->with('alert', $semesterAlert)
                ->withInput()
                ->with('need_confirm', true)
                ->with('option', 'template');
        }

        return $this->_executeGeneration(
            $templates,
            $validated['start_date'],
            $validated['end_date'],
            $targetSemester
        );
    }

    /**
     * Generate seances (Option B: from imported CSV).
     */
    public function generateFromImport(Request $request)
    {
        Log::info('generateFromImport called', ['has_file' => $request->hasFile('file'), 'inputs' => $request->except('file')]);
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:5120',
            'filiere_id' => 'required|exists:filieres,id',
            'groupe_id' => 'required|exists:groupes,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'target_semester' => 'required|in:S1,S2',
        ]);

        // Parse CSV into temp data (DO NOT save yet)
        $file = $request->file('file');
        $filiereId = $validated['filiere_id'];
        $groupeId = $validated['groupe_id'];
        $targetSemester = $validated['target_semester'];

        try {
            $csv = \League\Csv\Reader::createFromPath($file->getRealPath());
            $csv->setHeaderOffset(0);

            $dayMap = ['Lundi'=>1,'Mardi'=>2,'Mercredi'=>3,'Jeudi'=>4,'Vendredi'=>5,'Samedi'=>6];
            $templatesData = [];
            $errors = [];

            foreach ($csv->getRecords() as $index => $record) {
                try {
                    $dayOfWeek = $dayMap[$record['Jour']] ?? null;
                    if (!$dayOfWeek) {
                        $errors[] = "Ligne " . ($index + 2) . ": jour invalide";
                        continue;
                    }

                    $startTime = trim($record['Heure Début'] ?? '');
                    $endTime = trim($record['Heure Fin'] ?? '');
                    if (!$startTime || !$endTime || strtotime($startTime) >= strtotime($endTime)) {
                        $errors[] = "Ligne " . ($index + 2) . ": heures invalides";
                        continue;
                    }

                    $ue = \App\Models\Ue::where('code', $record['UE Code'])->first();
                    if (!$ue) {
                        $errors[] = "Ligne " . ($index + 2) . ": UE code non trouvée";
                        continue;
                    }

                    $salle = $record['Salle'] ? \App\Models\Salle::where('numero', $record['Salle'])->first() : null;
                    $enseignant = null;
                    if ($record['Enseignant']) {
                        $names = explode(' ', trim($record['Enseignant']), 2);
                        $enseignant = \App\Models\User::where('first_name', $names[0] ?? '')->where('last_name', $names[1] ?? '')->first();
                    }

                    $templatesData[] = [
                        'filiere_id' => $filiereId,
                        'groupe_id' => $groupeId,
                        'ue_id' => $ue->id,
                        'day_of_week' => $dayOfWeek,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'salle_id' => $salle?->id,
                        'enseignant_id' => $enseignant?->id,
                    ];
                } catch (\Exception $e) {
                    $errors[] = "Ligne " . ($index + 2) . ": " . $e->getMessage();
                }
            }

            if (count($errors) > 0) {
                return redirect()->back()->with('error', 'Erreurs lors de la lecture du CSV: ' . implode(', ', array_slice($errors, 0, 5)))->withInput();
            }

            if (count($templatesData) === 0) {
                return redirect()->back()->with('error', 'Aucun template valide trouvé dans le CSV')->withInput();
            }

            // Store parsed templates in session for confirmation
            session(['pending_templates' => $templatesData, 'pending_generation' => [
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'target_semester' => $targetSemester,
                'filiere_id' => $filiereId,
                'groupe_id' => $groupeId,
            ]]);

            $importAlert = "Vous allez générer " . count($templatesData) . " séance(s) pour le semestre {$targetSemester} du " . $validated['start_date'] . " au " . $validated['end_date'] . ".";

            if (!$request->get('confirm')) {
                Log::info('generateFromImport awaiting confirmation', ['count' => count($templatesData)]);
                return redirect()->back()->with('alert', $importAlert)->withInput()->with('need_confirm', true)->with('option', 'import');
            }

            // On confirm: retrieve pending templates and persist then generate
            Log::info('generateFromImport confirmed, persisting templates', ['pending_count' => count($pending)]);
            $pending = session('pending_templates', []);
            $savedTemplates = [];
            foreach ($pending as $data) {
                $template = SeanceTemplate::updateOrCreate(
                    [
                        'filiere_id' => $data['filiere_id'],
                        'groupe_id' => $data['groupe_id'],
                        'ue_id' => $data['ue_id'],
                        'day_of_week' => $data['day_of_week'],
                        'start_time' => $data['start_time'],
                        'semester' => $targetSemester,
                    ],
                    [
                        'end_time' => $data['end_time'],
                        'salle_id' => $data['salle_id'] ?? null,
                        'enseignant_id' => $data['enseignant_id'] ?? null,
                        'semester' => $targetSemester,
                    ]
                );
                $savedTemplates[] = $template;
            }

            // clear pending
            session()->forget('pending_templates');
            session()->forget('pending_generation');

            return $this->_executeGeneration($savedTemplates, $validated['start_date'], $validated['end_date'], $targetSemester);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la lecture du fichier: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Execute the actual generation logic.
     */
    private function _executeGeneration($templates, $startDate, $endDate, $semester)
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $startDate);
        $endDate = Carbon::createFromFormat('Y-m-d', $endDate);

        $createdCount = 0;
        $skippedCount = 0;
        $errors = [];

        // Only generate for matching day_of_week
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dayOfWeek = $date->dayOfWeek; // Laravel uses 0=Sunday, 1=Monday, etc.
            // Convert to our format: 1=Monday, ... 6=Saturday
            $adjustedDayOfWeek = ($dayOfWeek === 0) ? 6 : ($dayOfWeek);

            foreach ($templates as $template) {
                // Only create seance if template matches the day of week
                if ($template->day_of_week != $adjustedDayOfWeek) {
                    continue;
                }

                // Check if seance already exists: match by date, start_time, group and UE; include enseignant only if set
                $query = Seance::where('jour', $date->format('Y-m-d'))
                    ->where('heure_debut', $template->start_time)
                    ->where('groupe_id', $template->groupe_id)
                    ->where('ue_id', $template->ue_id);

                if ($template->enseignant_id) {
                    $query->where('enseignant_id', $template->enseignant_id);
                }

                $existingSeance = $query->first();

                if ($existingSeance) {
                    $skippedCount++;
                    continue;
                }

                // Create seance from template
                try {
                    $seance = Seance::create([
                        'ue_id' => $template->ue_id,
                        'jour' => $date->format('Y-m-d'),
                        'heure_debut' => $template->start_time,
                        'heure_fin' => $template->end_time,
                        'salle_id' => $template->salle_id,
                        'groupe_id' => $template->groupe_id,
                        'enseignant_id' => $template->enseignant_id,
                        'status' => 'planned',
                        'semester' => $semester,
                    ]);

                    // Copy template delegates to seance if any
                    if ($template->id && $template->delegates()->count() > 0) {
                        $seance->delegates()->attach($template->delegates()->pluck('users.id')->toArray());
                    }

                    $createdCount++;
                } catch (\Throwable $e) {
                    $errors[] = "Erreur pour " . $date->format('Y-m-d') . " à " . $template->start_time . ": " . $e->getMessage();
                    $skippedCount++;
                }
            }
        }

        $message = "Séances générées! Créées: {$createdCount}, Déjà existantes: {$skippedCount}";
        if (count($errors) > 0) {
            $message .= "\n\nErreurs: " . implode("\n", array_slice($errors, 0, 5));
            return redirect()->route('admin.seances.index')
                ->with('success', $message)
                ->with('errors', $errors);
        }

        return redirect()->route('admin.seances.index')->with('success', $message);
    }
}
