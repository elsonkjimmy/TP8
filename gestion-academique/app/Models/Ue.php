<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ue extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'nom',
        'filiere_id',
        'enseignant_id',
    ];

    /**
     * Get the filiere that owns the Ue.
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    /**
     * Get the teacher that teaches the Ue.
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Get the sessions for the UE.
     */
    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    /**
     * Calculate the progress of the UE.
     *
     * @return float
     */
    public function getProgressAttribute(): float
    {
        $totalSeances = $this->seances->count();
        if ($totalSeances === 0) {
            return 0.0;
        }

        $completedSeances = $this->seances()->where('status', 'completed')->count();

        return round(($completedSeances / $totalSeances) * 100, 2);
    }
}
