<?php

namespace App\Http\Controllers\Delegate;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\RapportSeance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DelegateController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $today = Carbon::now();
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);
        $sunday = $today->copy()->endOfWeek(Carbon::SUNDAY);

        // Seances this week where user is a delegate.
        // Include both directly-assigned delegates and template-based delegates by
        // loading all seances in the week and filtering via `effectiveDelegates()`.
        $seances = Seance::with(['ue','groupe','salle','rapportSeance'])
            ->whereBetween('jour', [$monday->format('Y-m-d'), $sunday->format('Y-m-d')])
            ->get()
            ->filter(function ($seance) use ($user) {
                try {
                    return $seance->effectiveDelegates()->contains('id', $user->id);
                } catch (\Throwable $e) {
                    return false;
                }
            })->values();

        // Draft reports filled by this delegate - try modern schema, fall back to legacy columns
        try {
            $draftReports = RapportSeance::with('seance')->where('filled_by_id', $user->id)->where('status', 'draft')->get();
        } catch (\Throwable $e) {
            // Fallback for older schema: use `delegue_id` and French `statut` column
            try {
                $draftReports = RapportSeance::with('seance')->where('delegue_id', $user->id)->where('statut', 'draft')->get();
            } catch (\Throwable $e) {
                $draftReports = collect();
            }
        }

        // Submitted reports (awaiting teacher validation)
        try {
            $reportsToValidate = RapportSeance::with('seance')->where('filled_by_id', $user->id)->where('status', 'submitted')->get();
        } catch (\Throwable $e) {
            try {
                $reportsToValidate = RapportSeance::with('seance')->where('delegue_id', $user->id)->where('statut', 'submitted')->get();
            } catch (\Throwable $e) {
                $reportsToValidate = collect();
            }
        }

        // Determine the delegate's main groupe (if any) based on their assigned seances
        $groupe = null;
        if ($seances->isNotEmpty()) {
            $firstSeance = $seances->first();
            $groupe = $firstSeance->groupe ?? null;
        }

        return view('delegate.dashboard', compact('seances', 'draftReports', 'reportsToValidate', 'groupe'));
    }

    /**
     * Edit a report filled by the delegate.
     */
    public function editReport($reportId)
    {
        $report = RapportSeance::with('seance.ue.chapters')->findOrFail($reportId);
        $user = Auth::user();

        // Only the delegate who created the report can edit it
        if ($report->filled_by_id !== $user->id && $report->delegue_id !== $user->id) {
            abort(403);
        }

        // Can only edit if not validated
        if ($report->status === 'validated') {
            return redirect()->back()->with('error', 'Impossible de modifier un rapport validé.');
        }

        return view('delegate.reports.edit', compact('report'));
    }

    /**
     * Update a report filled by the delegate.
     */
    public function updateReport(Request $request, $reportId)
    {
        $report = RapportSeance::findOrFail($reportId);
        $user = Auth::user();

        // Only the delegate who created the report can update it
        if ($report->filled_by_id !== $user->id && $report->delegue_id !== $user->id) {
            abort(403);
        }

        // Can only update if not validated
        if ($report->status === 'validated') {
            return redirect()->back()->with('error', 'Impossible de modifier un rapport validé.');
        }

        $data = $request->validate([
            'contenu' => 'nullable|string',
            'chapter_id' => 'nullable|exists:chapters,id',
        ]);

        $report->update([
            'contenu' => $data['contenu'] ?? $report->contenu,
            'chapter_id' => $data['chapter_id'] ?? $report->chapter_id,
        ]);

        return redirect()->route('delegate.dashboard')->with('success', 'Rapport mis à jour.');
    }

    /**
     * Delete a report filled by the delegate.
     */
    public function deleteReport($reportId)
    {
        $report = RapportSeance::findOrFail($reportId);
        $user = Auth::user();

        // Only the delegate who created the report can delete it
        if ($report->filled_by_id !== $user->id && $report->delegue_id !== $user->id) {
            abort(403);
        }

        // Can only delete if not validated
        if ($report->status === 'validated') {
            return redirect()->back()->with('error', 'Impossible de supprimer un rapport validé.');
        }

        $report->delete();

        return redirect()->route('delegate.dashboard')->with('success', 'Rapport supprimé.');
    }
}
