<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupeEffectif extends Model
{
    use HasFactory;

    protected $table = 'groupe_effectifs';

    protected $fillable = [
        'groupe_id',
        'annee',
        'semestre',
        'effectif',
    ];

    /**
     * Get the groupe that owns this effectif.
     */
    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
}
