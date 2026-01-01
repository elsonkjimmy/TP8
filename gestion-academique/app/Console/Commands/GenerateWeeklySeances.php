<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SeanceTemplate;
use App\Models\Seance;
use Carbon\Carbon;

class GenerateWeeklySeances extends Command
{
    protected $signature = 'seances:generate-weekly';
    protected $description = 'Generate dated Seance records for the upcoming week from templates';

    public function handle()
    {
        $today = Carbon::now();
        $monday = $today->copy()->startOfWeek(Carbon::MONDAY);

        $templates = SeanceTemplate::with(['ue','salle','groupe','enseignant'])->get();

        foreach ([1,2,3,4,5,6] as $dow) {
            $date = $monday->copy()->addDays($dow - 1)->format('Y-m-d');

            $dayTemplates = $templates->where('day_of_week', $dow);
            foreach ($dayTemplates as $template) {
                // Create seance only if not exists for that date/time/groupe
                $exists = Seance::where('ue_id', $template->ue_id)
                    ->where('jour', $date)
                    ->where('heure_debut', $template->start_time)
                    ->where('groupe_id', $template->groupe_id)
                    ->exists();

                if (! $exists) {
                    $seance = Seance::create([
                        'ue_id' => $template->ue_id,
                        'jour' => $date,
                        'heure_debut' => $template->start_time,
                        'heure_fin' => $template->end_time,
                        'salle_id' => $template->salle_id,
                        'groupe_id' => $template->groupe_id,
                        'enseignant_id' => $template->enseignant_id,
                        'status' => 'scheduled',
                    ]);

                    // copy template delegates into seance_delegates if any
                    if (method_exists($template, 'delegates')) {
                        $template->load('delegates');
                        $delegateIds = $template->delegates->pluck('id')->toArray();
                        if (! empty($delegateIds)) {
                            $seance->delegates()->attach($delegateIds);
                        }
                    }
                }
            }
        }

        $this->info('Weekly seances generated.');
        return 0;
    }
}
