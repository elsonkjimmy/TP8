<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ue_id',
        'jour',
        'heure_debut',
        'heure_fin',
        'salle_id',
        'groupe_id',
        'group_divisions',
        'enseignant_id',
        'status',
        'semester',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jour' => 'date',
        'heure_debut' => 'datetime:H:i',
        'heure_fin' => 'datetime:H:i',
    ];

    /**
     * Get the UE that owns the Seance.
     */
    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }

    /**
     * Get the Salle that owns the Seance.
     */
    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    /**
     * Get the Groupe that owns the Seance.
     */
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    /**
     * Get the Enseignant that owns the Seance.
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Get the report associated with the Seance.
     */
    public function rapportSeance()
    {
        return $this->hasOne(RapportSeance::class, 'seance_id');
    }

    /**
     * Get the delegates assigned to this Seance.
     */
    public function delegates()
    {
        return $this->belongsToMany(User::class, 'seance_delegates', 'seance_id', 'user_id')->withTimestamps();
    }

    /**
     * Try to find delegates defined on a matching SeanceTemplate (fallback).
     */
    public function templateDelegates()
    {
        try {
            $dow = \Carbon\Carbon::parse($this->jour)->dayOfWeekIso; // 1 (Mon) .. 7 (Sun)
            $time = \Carbon\Carbon::parse($this->heure_debut)->format('H:i');

            $template = \App\Models\SeanceTemplate::where('groupe_id', $this->groupe_id)
                ->where('enseignant_id', $this->enseignant_id)
                ->where('start_time', $time)
                ->first();

            if ($template) {
                return $template->delegates;
            }
        } catch (\Throwable $e) {
            return collect();
        }

        return collect();
    }

    public function effectiveDelegates()
    {
        $delegates = $this->delegates()->get();
        if ($delegates->isNotEmpty()) {
            return $delegates;
        }
        return $this->templateDelegates();
    }
}
