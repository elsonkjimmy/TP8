<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Seance;
use Carbon\Carbon;

class MarkMissedSeances extends Command
{
    protected $signature = 'seances:mark-missed';
    protected $description = 'Mark seances that passed today without a report as missed';

    public function handle()
    {
        $today = Carbon::now()->format('Y-m-d');

        $seances = Seance::where('jour', $today)
            ->whereNotIn('status', ['completed','cancelled'])
            ->get();

        foreach ($seances as $seance) {
            // if no report exists and end time passed
            if (! $seance->rapportSeance && Carbon::parse($seance->heure_fin)->lt(Carbon::now())) {
                $seance->status = 'missed';
                $seance->save();
            }
        }

        $this->info('Missed seances processed.');
        return 0;
    }
}
