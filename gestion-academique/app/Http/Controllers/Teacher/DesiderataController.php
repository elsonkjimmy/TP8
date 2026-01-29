<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Desiderata;
use App\Models\SeanceTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DesiderataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desideratas = Desiderata::with(['seanceTemplate.ue', 'seanceTemplate.groupe'])
            ->where('enseignant_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('teacher.desideratas.index', compact('desideratas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get UEs assigned to the teacher
        $ues = \App\Models\Ue::where('enseignant_id', Auth::id())->with('filiere', 'groupe')->get();
        // Get all rooms
        $salles = \App\Models\Salle::all();

        return view('teacher.desideratas.create', compact('ues', 'salles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ue_id' => 'required|exists:ues,id',
            'salle_id' => 'nullable|exists:salles,id',
            'day_of_week' => 'required|integer|min:1|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'comment' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $ue = \App\Models\Ue::findOrFail($validated['ue_id']);
        
        // Ensure teacher teaches this UE
        // if ($ue->enseignant_id !== $user->id) { ... } // Optional check

        // Create the SeanceTemplate for this request
        $template = SeanceTemplate::create([
            'filiere_id' => $ue->filiere_id,
            'groupe_id' => $ue->groupe_id,
            'ue_id' => $ue->id,
            'salle_id' => $validated['salle_id'],
            'enseignant_id' => $user->id,
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'comment' => $validated['comment'],
        ]);

        // Check for Conflicts
        $conflictResult = $this->checkConflicts($template);

        $status = 'pending';
        $message = 'Votre désirata a été enregistré.';

        if ($conflictResult['status'] === 'approved') {
            $status = 'approved';
            $message = 'Votre désirata a été validé (Aucun conflit).';
        } elseif ($conflictResult['status'] === 'rejected') {
            $status = 'rejected';
            $message = 'Votre désirata a été rejeté : ' . $conflictResult['reason'];
        } else {
            // Pending with conflict warning?
            $message = 'Votre désirata est en attente de validation (Conflit de priorité).';
        }

        Desiderata::create([
            'enseignant_id' => $user->id,
            'seance_template_id' => $template->id,
            'status' => $status,
            'comment' => $validated['comment'] ?? null,
        ]);

        return back()->with('success', $message);
    }

    private function checkConflicts(SeanceTemplate $newTemplate)
    {
        // 1. Check against APPROVED Desideratas (Hard Constraint)
        $approvedConflicts = Desiderata::with('seanceTemplate')
            ->where('status', 'approved')
            ->whereHas('seanceTemplate', function ($query) use ($newTemplate) {
                $this->applyOverlapQuery($query, $newTemplate);
            })
            ->get();

        if ($approvedConflicts->isNotEmpty()) {
            return ['status' => 'rejected', 'reason' => 'Conflit avec un créneau déjà validé.'];
        }

        // 2. Check against PENDING Desideratas (Priority Constraint)
        $pendingConflicts = Desiderata::with(['seanceTemplate.filiere'])
            ->where('status', 'pending')
            ->whereHas('seanceTemplate', function ($query) use ($newTemplate) {
                $this->applyOverlapQuery($query, $newTemplate);
            })
            ->get();

        if ($pendingConflicts->isEmpty()) {
            return ['status' => 'approved', 'reason' => 'OK'];
        }

        // 3. Resolve Priority
        // Priority Rule: match input requirements.
        // Morning (< 12:00): Low Level (L1) > High Level (M2)
        // Afternoon (>= 12:00): High Level (M2) > Low Level (L1)
        
        $newLevel = $this->getFiliereLevel($newTemplate->filiere->code);
        $isMorning = $newTemplate->start_time < '12:00:00';

        foreach ($pendingConflicts as $conflict) {
            $conflictLevel = $this->getFiliereLevel($conflict->seanceTemplate->filiere->code);
            
            $newWins = false;

            if ($isMorning) {
                // Lower level wins (1 < 5)
                if ($newLevel < $conflictLevel) $newWins = true;
            } else {
                // Higher level wins (5 > 1)
                if ($newLevel > $conflictLevel) $newWins = true;
            }

            if (!$newWins) {
                // If we lose against ANY pending conflict, we assume existing pending holds its ground 
                // (or strictly, if we are equal? FIFO).
                if ($newLevel == $conflictLevel) {
                     return ['status' => 'pending', 'reason' => 'Priorité égale.'];
                }
                return ['status' => 'rejected', 'reason' => 'Priorité insuffisante pour ce créneau [' . ($isMorning ? 'Matin: Niv Inférieur Prio' : 'Soir: Niv Supérieur Prio') . ']'];
            }
        }

        // If we win against ALL pending conflicts:
        // Reject the others? Or just Approve ours?
        // Let's Reject the conflicting ones to clean up.
        foreach ($pendingConflicts as $conflict) {
            $conflict->update(['status' => 'rejected', 'comment' => 'Rejeté au profit d\'une priorité supérieure.']);
        }

        return ['status' => 'approved', 'reason' => 'Validé par priorité.'];
    }

    private function applyOverlapQuery($query, $template)
    {
        $query->where('day_of_week', $template->day_of_week)
              ->where(function ($q) use ($template) {
                  $q->whereBetween('start_time', [$template->start_time, $template->end_time])
                    ->orWhereBetween('end_time', [$template->start_time, $template->end_time])
                    ->orWhere(function($sq) use ($template) {
                        $sq->where('start_time', '<=', $template->start_time)
                           ->where('end_time', '>=', $template->end_time);
                    });
              })
              ->where(function($q) use ($template) {
                  // Resource Conflict: Room OR Teacher OR Group
                  if ($template->salle_id) {
                      $q->where('salle_id', $template->salle_id);
                  }
                  // Teacher cannot clone self (though User check usually covers this, explicit check is good)
                  $q->orWhere('enseignant_id', $template->enseignant_id);
                  // Group cannot be in 2 places
                  if ($template->groupe_id) {
                      $q->orWhere('groupe_id', $template->groupe_id);
                  }
              });
    }

    private function getFiliereLevel($code)
    {
        // format: INFO-L1, INFO-M2
        if (str_contains($code, 'L1')) return 1;
        if (str_contains($code, 'L2')) return 2;
        if (str_contains($code, 'L3')) return 3;
        if (str_contains($code, 'M1')) return 4;
        if (str_contains($code, 'M2')) return 5;
        return 99; // Unknown
    }
}
