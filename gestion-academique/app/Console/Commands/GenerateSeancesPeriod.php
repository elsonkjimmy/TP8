<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SeanceTemplate;
use App\Models\Seance;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class GenerateSeancesPeriod extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seances:generate-period {startDate} {endDate} {--semester=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate seances from templates for a given period (date range). Optionally filter by semester.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $this->argument('startDate'));
        $endDate = Carbon::createFromFormat('Y-m-d', $this->argument('endDate'));
        $semester = $this->option('semester');

        if (!$startDate || !$endDate) {
            $this->error('Invalid date format. Use Y-m-d (e.g., 2026-01-01).');
            return 1;
        }

        if ($startDate > $endDate) {
            $this->error('Start date must be before end date.');
            return 1;
        }

        $this->info("Generating seances from {$startDate->format('Y-m-d')} to {$endDate->format('Y-m-d')}");
        if ($semester) {
            $this->info("Filter: semester = '{$semester}'");
        }

        // Get templates to use
        $query = SeanceTemplate::query();
        if ($semester) {
            $query->where('semester', $semester);
        }
        $templates = $query->get();

        if ($templates->isEmpty()) {
            $this->warn('No templates found matching criteria.');
            return 0;
        }

        $this->info("Found {$templates->count()} template(s).");

        $createdCount = 0;
        $skippedCount = 0;

        // Iterate through each day in the period
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $date) {
            $dayOfWeek = $date->dayOfWeekIso; // 1 (Mon) .. 7 (Sun)
            $dayName = $date->format('l'); // 'Monday', 'Tuesday', etc.

            // Find templates for this day of week
            $dayTemplates = $templates->filter(function ($template) use ($dayOfWeek, $dayName) {
                // Map day names to match template day_of_week if available
                // Or match by day_of_week column
                return isset($template->day_of_week) && $template->day_of_week == $dayOfWeek;
            });

            // If no day_of_week column, try matching by start_time (heuristic: templates with same day pattern)
            // For now, generate all templates for each day (can be refined)
            if ($dayTemplates->isEmpty()) {
                // Include all templates if no day-specific filtering
                $dayTemplates = $templates;
            }

            foreach ($dayTemplates as $template) {
                // Check if seance already exists for this date/time
                $existingSeance = Seance::where('jour', $date->format('Y-m-d'))
                    ->where('heure_debut', $template->start_time)
                    ->where('groupe_id', $template->groupe_id)
                    ->where('enseignant_id', $template->enseignant_id)
                    ->first();

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
                    ]);

                    // Copy template delegates to seance if any
                    if ($template->delegates()->count() > 0) {
                        $seance->delegates()->attach($template->delegates()->pluck('users.id')->toArray());
                    }

                    $createdCount++;
                } catch (\Throwable $e) {
                    $this->warn("Error creating seance for {$date->format('Y-m-d')} {$template->start_time}: {$e->getMessage()}");
                }
            }
        }

        $this->info("Done! Created: {$createdCount}, Skipped: {$skippedCount}");
        return 0;
    }
}
