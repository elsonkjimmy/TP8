<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Seance;
use App\Models\RapportSeance;
use App\Models\Notification;
use Carbon\Carbon;

class MarkMissedSeances extends Command
{
    protected $signature = 'seances:mark-missed';
    protected $description = 'Create reports for seances without reports after their end time';

    public function handle()
    {
        $today = Carbon::now();

        // Consider any seance that is in the past (jour < today) or today but ended already,
        // and that isn't completed/cancelled, and that has no report.
        $seances = Seance::whereDate('jour', '<=', $today->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        foreach ($seances as $seance) {
            // skip if a report already exists
            if ($seance->rapportSeance) {
                continue;
            }

            $seanceDate = Carbon::parse($seance->jour)->startOfDay();

            // Check if the seance day is before today OR if it's today and the end time already passed
            $shouldCreateReport = false;
            if ($seanceDate->lt($today->copy()->startOfDay())) {
                $shouldCreateReport = true;
            } else {
                // same day: check heure_fin
                try {
                    $end = Carbon::parse($seance->heure_fin);
                } catch (\Throwable $e) {
                    $end = null;
                }
                if ($end && $end->lt(Carbon::now())) {
                    $shouldCreateReport = true;
                }
            }

            if ($shouldCreateReport) {
                // Create a report with status "annulé" and content "séance non faite"
                RapportSeance::create([
                    'seance_id' => $seance->id,
                    'contenu' => 'Séance non faite',
                    'status' => 'annulé',
                ]);

                // Mark seance as no_fait
                $seance->status = 'no_fait';
                $seance->save();

                // Notify all admins
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    Notification::create([
                        'expediteur_id' => null,
                        'destinataire_id' => $admin->id,
                        'contenu' => sprintf(
                            "Rapport créé pour la séance non faite du %s (%s) - Groupe %s",
                            Carbon::parse($seance->jour)->format('d/m/Y'),
                            optional($seance->ue)->nom ?? 'UE',
                            optional($seance->groupe)->nom ?? ''
                        ),
                    ]);
                }

                $this->line("✓ Rapport créé et admin notifié pour séance du " . Carbon::parse($seance->jour)->format('d/m/Y'));
            }
        }

        $this->info('Missed seances processed.');
        return 0;
    }
}
