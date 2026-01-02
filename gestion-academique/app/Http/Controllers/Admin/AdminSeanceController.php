<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Groupe;
use App\Models\Salle;
use App\Models\Seance;
use App\Models\Ue;
use App\Models\User;
use App\Models\Notification; // Import Notification model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon; // Import Carbon
use Illuminate\Support\Facades\Auth; // Import Auth

class AdminSeanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Seance::with(['ue', 'salle', 'groupe', 'enseignant']);

        // Apply filters
        if (request('filiere_id')) {
            $query->whereHas('groupe', function ($q) {
                $q->where('filiere_id', request('filiere_id'));
            });
        }
        if (request('groupe_id')) {
            $query->where('groupe_id', request('groupe_id'));
        }
        if (request('semester')) {
            $query->where('semester', request('semester'));
        }

        $seances = $query->get();
        $filieres = \App\Models\Filiere::all();
        $groupes = \App\Models\Groupe::all();
        $semesters = ['S1', 'S2'];

        return view('admin.seances.index', compact('seances', 'filieres', 'groupes', 'semesters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $ues = Ue::all();
        $salles = Salle::all();
        $groupes = Groupe::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.seances.create', compact('ues', 'salles', 'groupes', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'ue_id' => ['required', 'exists:ues,id'],
            'jour' => ['required', 'date'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
            'salle_id' => ['required', 'exists:salles,id'],
            'groupe_id' => ['required', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'semester' => ['nullable', 'in:S1,S2'],
        ]);

        $conflicts = $this->checkConflicts($validatedData);

        if (!empty($conflicts)) {
            return redirect()->back()->withErrors($conflicts)->withInput();
        }

        try {
            $seance = Seance::create($validatedData);

            $this->sendSeanceNotification($seance, 'created');

            return redirect()->route('admin.seances.index')->with('success', 'Séance créée avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de séance', [
                'validated_data' => $validatedData,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()
                ->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seance $seance)
    {
        // Not typically used for Seance management in this context
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seance $seance)
    {
        $ues = Ue::all();
        $salles = Salle::all();
        $groupes = Groupe::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.seances.edit', compact('seance', 'ues', 'salles', 'groupes', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seance $seance)
    {
        $validatedData = $request->validate([
            'ue_id' => ['required', 'exists:ues,id'],
            'jour' => ['required', 'date'],
            'heure_debut' => ['required', 'date_format:H:i'],
            'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
            'salle_id' => ['required', 'exists:salles,id'],
            'groupe_id' => ['required', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'semester' => ['nullable', 'in:S1,S2'],
        ]);

        $conflicts = $this->checkConflicts($validatedData, $seance);

        if (!empty($conflicts)) {
            return redirect()->back()->withErrors($conflicts)->withInput();
        }

        $seance->update($validatedData);

        $this->sendSeanceNotification($seance, 'updated');

        return redirect()->route('admin.seances.index')->with('success', 'Séance mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seance $seance)
    {
        $seance->delete();
        $this->sendSeanceNotification($seance, 'deleted');
        return redirect()->route('admin.seances.index')->with('success', 'Séance supprimée avec succès.');
    }

    /**
     * Check for scheduling conflicts.
     *
     * @param array $data
     * @param Seance|null $currentSeance
     * @return array
     */
    protected function checkConflicts(array $data, ?Seance $currentSeance = null): array
    {
        $errors = [];

        $proposedStart = Carbon::parse($data['jour'] . ' ' . $data['heure_debut']);
        $proposedEnd = Carbon::parse($data['jour'] . ' ' . $data['heure_fin']);

        $query = Seance::where('jour', $data['jour'])
            ->where(function ($q) use ($proposedStart, $proposedEnd) {
                $q->where(function ($q2) use ($proposedStart, $proposedEnd) {
                    $q2->where('heure_debut', '<', $proposedEnd)
                       ->where('heure_fin', '>', $proposedStart);
                });
            });

        if ($currentSeance) {
            $query->where('id', '!=', $currentSeance->id);
        }

        $conflictingSeances = $query->get();

        foreach ($conflictingSeances as $existingSeance) {
            if ($existingSeance->salle_id == $data['salle_id']) {
                $errors[] = 'Conflit de salle : La salle ' . $existingSeance->salle->numero . ' est déjà occupée de ' . $existingSeance->heure_debut->format('H:i') . ' à ' . $existingSeance->heure_fin->format('H:i') . '.';
            }
            if ($existingSeance->enseignant_id == $data['enseignant_id']) {
                $errors[] = 'Conflit d\'enseignant : L\'enseignant ' . $existingSeance->enseignant->first_name . ' ' . $existingSeance->enseignant->last_name . ' est déjà assigné à une autre séance de ' . $existingSeance->heure_debut->format('H:i') . ' à ' . $existingSeance->heure_fin->format('H:i') . '.';
            }
            if ($existingSeance->groupe_id == $data['groupe_id']) {
                $errors[] = 'Conflit de groupe : Le groupe ' . $existingSeance->groupe->nom . ' est déjà assigné à une autre séance de ' . $existingSeance->heure_debut->format('H:i') . ' à ' . $existingSeance->heure_fin->format('H:i') . '.';
            }
        }

        return array_unique($errors);
    }

    /**
     * Send notifications about a session change.
     *
     * @param Seance $seance
     * @param string $type 'created', 'updated', 'deleted'
     * @return void
     */
    protected function sendSeanceNotification(Seance $seance, string $type): void
    {
        $admin = Auth::user(); // The admin performing the action

        $message = '';
        switch ($type) {
            case 'created':
                $message = "Une nouvelle séance a été créée : UE {$seance->ue->nom} le {$seance->jour->format('d/m/Y')} de {$seance->heure_debut->format('H:i')} à {$seance->heure_fin->format('H:i')} en salle {$seance->salle->numero} pour le groupe {$seance->groupe->nom}.";
                break;
            case 'updated':
                $message = "La séance de l'UE {$seance->ue->nom} le {$seance->jour->format('d/m/Y')} a été modifiée. Nouvelle heure: {$seance->heure_debut->format('H:i')} - {$seance->heure_fin->format('H:i')}, Salle: {$seance->salle->numero}, Groupe: {$seance->groupe->nom}.";
                break;
            case 'deleted':
                $message = "La séance de l'UE {$seance->ue->nom} le {$seance->jour->format('d/m/Y')} de {$seance->heure_debut->format('H:i')} à {$seance->heure_fin->format('H:i')} en salle {$seance->salle->numero} pour le groupe {$seance->groupe->nom} a été annulée.";
                break;
        }

        // Notify the assigned teacher
        Notification::create([
            'expediteur_id' => $admin->id,
            'destinataire_id' => $seance->enseignant_id,
            'contenu' => $message,
        ]);

        // Notify all users in the group (assuming students are users with role 'student')
        // This requires a relationship from Groupe to Users (students)
        // For now, let's assume we notify the delegate of the group if available
        if ($seance->groupe->delegue_id) {
            Notification::create([
                'expediteur_id' => $admin->id,
                'destinataire_id' => $seance->groupe->delegue_id,
                'contenu' => $message,
            ]);
        }

        // Notify all users in the filiere (e.g., all teachers and delegates in that filiere)
        // This requires fetching users by filiere
        // For now, let's skip this for simplicity, or notify the filiere's responsable teacher
        if ($seance->groupe->filiere->enseignant_responsable_id && $seance->groupe->filiere->enseignant_responsable_id !== $seance->enseignant_id) {
             Notification::create([
                'expediteur_id' => $admin->id,
                'destinataire_id' => $seance->groupe->filiere->enseignant_responsable_id,
                'contenu' => $message,
            ]);
        }
    }
}