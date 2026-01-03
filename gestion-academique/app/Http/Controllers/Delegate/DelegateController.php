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

        // Seances this week where user is a delegate
        $seances = Seance::with(['ue','groupe','salle','rapportSeance'])
            ->whereBetween('jour', [$monday->format('Y-m-d'), $sunday->format('Y-m-d')])
            ->whereHas('delegates', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })->get();

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
            $submittedReports = RapportSeance::with('seance')->where('filled_by_id', $user->id)->where('status', 'submitted')->get();
        } catch (\Throwable $e) {
            try {
                $submittedReports = RapportSeance::with('seance')->where('delegue_id', $user->id)->where('statut', 'submitted')->get();
            } catch (\Throwable $e) {
                $submittedReports = collect();
            }
        }

        return view('delegate.dashboard', compact('seances', 'draftReports', 'submittedReports'));
    }
}
