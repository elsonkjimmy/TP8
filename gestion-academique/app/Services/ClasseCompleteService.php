<?php

namespace App\Services;

use App\Models\Groupe;
use App\Models\Ue;
use App\Models\Seance;
use Illuminate\Database\Eloquent\Collection;

class ClasseCompleteService
{
    /**
     * Check if a groupe has all its UEs scheduled for a semester
     */
    public static function isClasseComplete(Groupe $groupe, string $semestre, int $annee): bool
    {
        // Get all UEs for the groupe's filiere
        $allUes = Ue::where('filiere_id', $groupe->filiere_id)->pluck('id')->toArray();

        if (empty($allUes)) {
            return false;
        }

        // Get UEs that have at least one seance scheduled for this groupe in this semester
        // Note: annee is extracted from jour column using YEAR() function
        $scheduledUes = Seance::where('groupe_id', $groupe->id)
            ->where('semester', $semestre)
            ->whereRaw('YEAR(jour) = ?', [$annee])
            ->distinct('ue_id')
            ->pluck('ue_id')
            ->toArray();

        // Check if all UEs are scheduled
        return count(array_intersect($allUes, $scheduledUes)) >= count($allUes);
    }

    /**
     * Get all complete classes for a given year
     */
    public static function getCompleteClasses(int $annee): array
    {
        $semesters = ['S1', 'S2'];
        $completeClasses = [];

        foreach ($semesters as $semestre) {
            $groupes = Groupe::all();

            foreach ($groupes as $groupe) {
                if (self::isClasseComplete($groupe, $semestre, $annee)) {
                    $completeClasses[] = [
                        'groupe' => $groupe,
                        'semestre' => $semestre,
                        'annee' => $annee,
                    ];
                }
            }
        }

        return $completeClasses;
    }

    /**
     * Get UEs scheduled for a groupe/semester
     */
    public static function getScheduledUes(Groupe $groupe, string $semestre, int $annee): Collection
    {
        return Ue::whereIn('id', function ($query) use ($groupe, $annee, $semestre) {
            $query->select('ue_id')
                ->from('seances')
                ->where('groupe_id', $groupe->id)
                ->where('semester', $semestre)
                ->whereRaw('YEAR(jour) = ?', [$annee]);
        })
        ->get();
    }

    /**
     * Get UEs NOT scheduled for a groupe/semester
     */
    public static function getMissingUes(Groupe $groupe, string $semestre, int $annee): Collection
    {
        $scheduledUeIds = self::getScheduledUes($groupe, $semestre, $annee)
            ->pluck('id')
            ->toArray();

        return Ue::where('filiere_id', $groupe->filiere_id)
            ->whereNotIn('id', $scheduledUeIds)
            ->get();
    }
}
