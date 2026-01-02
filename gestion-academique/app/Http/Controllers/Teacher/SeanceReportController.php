<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\RapportSeance;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeanceReportController extends Controller
{
    public function create($seanceId)
    {
        $seance = Seance::with(['ue.chapters','groupe','salle','delegates'])->findOrFail($seanceId);
        $user = Auth::user();

        // authorization: only assigned teacher or their delegate can create
        $isDelegate = $seance->effectiveDelegates()->contains($user->id);
        if ($seance->enseignant_id !== $user->id && ! $isDelegate) {
            abort(403);
        }

        return view('teacher.reports.create', compact('seance'));
    }

    public function store(Request $request, $seanceId)
    {
        $seance = Seance::findOrFail($seanceId);
        $seance->load('delegates');
        $user = Auth::user();

        $isDelegate = $seance->effectiveDelegates()->contains($user->id);
        if ($seance->enseignant_id !== $user->id && ! $isDelegate) {
            abort(403);
        }

        $data = $request->validate([
            'contenu' => 'nullable|string',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        // Determine workflow: if teacher submits -> validated and mark seance completed; if delegate -> submitted
        if ($user->id === $seance->enseignant_id) {
            $status = 'validated';
        } else {
            $status = 'submitted';
        }

        $report = RapportSeance::updateOrCreate([
            'seance_id' => $seance->id,
        ], [
            'enseignant_id' => $seance->enseignant_id,
            'filled_by_id' => $user->id,
            // legacy support: set delegue_id when a delegate fills the report
            'delegue_id' => $isDelegate ? $user->id : null,
            'contenu' => $data['contenu'] ?? null,
            'chapter_id' => $data['chapter_id'] ?? null,
            'status' => $status,
            // write legacy column too to satisfy older schemas
            'statut' => $status,
        ]);

        if ($status === 'submitted') {
            // notify the enseignant that a delegate submitted a report requiring validation
            try {
                Notification::create([
                    'expediteur_id' => $user->id,
                    'destinataire_id' => $seance->enseignant_id,
                    'contenu' => "Nouveau rapport soumis pour validation pour la séance (" . ($seance->ue->nom ?? 'UE') . ")"
                ]);
            } catch (\Throwable $e) {
                // ignore
            }
            return redirect()->route('teacher.dashboard')->with('success', 'Rapport envoyé (en attente de validation).');
        }

        if ($status === 'validated') {
            $report->validated_by_id = $user->id;
            $report->validated_at = now();
            // keep legacy column in sync
            $report->statut = 'validated';
            $report->save();

            // mark seance as completed
            $seance->status = 'completed';
            $seance->save();

            // Notify all admins via Notification model
            try {
                $admins = User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'expediteur_id' => $user->id,
                        'destinataire_id' => $admin->id,
                        'contenu' => "Rapport validé pour la séance (" . ($seance->ue->nom ?? 'UE') . ") le " . now()->format('d/m/Y H:i') . " par " . ($user->name ?? 'enseignant')
                    ]);
                }
            } catch (\Throwable $e) {
                // ignore notification errors
            }

            return redirect()->route('teacher.dashboard')->with('success', 'Rapport envoyé et séance marquée comme effectuée.');
        }

        return redirect()->route('teacher.dashboard')->with('success', 'Rapport envoyé (en attente de validation).');
    }

    public function validateReport(Request $request, $reportId)
    {
        $report = RapportSeance::findOrFail($reportId);
        $user = Auth::user();

        // only the enseignant (owner of the seance) or admin can validate
        $isAdmin = (isset($user->role) && $user->role === 'admin') || (method_exists($user, 'hasRole') && $user->hasRole('admin'));
        if ($report->seance->enseignant_id !== $user->id && ! $isAdmin) {
            abort(403);
        }

        $report->status = 'validated';
        $report->validated_by_id = $user->id;
        $report->validated_at = now();
        $report->save();

        // mark seance as completed
        $report->seance->status = 'completed';
        $report->seance->save();

        // Notify admins
        try {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Notification::create([
                    'expediteur_id' => $user->id,
                    'destinataire_id' => $admin->id,
                    'contenu' => "Rapport validé pour la séance (" . ($report->seance->ue->nom ?? 'UE') . ") le " . now()->format('d/m/Y H:i') . " par " . ($user->name ?? 'enseignant')
                ]);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return back()->with('success', 'Rapport validé.');
    }

    public function show($reportId)
    {
        $rapportSeance = RapportSeance::with(['seance.ue', 'seance.groupe', 'seance.salle', 'seance.delegates', 'enseignant', 'delegue', 'chapter'])->findOrFail($reportId);
        $user = Auth::user();

        // Authorization: teacher owner, the user who filled it, a delegate, or admin
        $isOwner = $rapportSeance->seance->enseignant_id === $user->id;
        $isFiller = $rapportSeance->filled_by_id === $user->id;
        $isDelegate = $rapportSeance->seance->delegates->contains($user->id);
        $isAdmin = (isset($user->role) && $user->role === 'admin') || (method_exists($user, 'hasRole') && $user->hasRole('admin'));

        if (! ($isOwner || $isFiller || $isDelegate || $isAdmin)) {
            abort(403);
        }

        return view('teacher.reports.show', compact('rapportSeance'));
    }
}
