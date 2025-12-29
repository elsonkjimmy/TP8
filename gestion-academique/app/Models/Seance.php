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
        'enseignant_id',
        'status', // Add status to fillable
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
}
