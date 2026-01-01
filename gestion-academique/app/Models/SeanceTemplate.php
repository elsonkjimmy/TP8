<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeanceTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'filiere_id',
        'groupe_id',
        'ue_id',
        'salle_id',
        'enseignant_id',
        'day_of_week',
        'start_time',
        'end_time',
        'comment',
    ];

    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    public function delegates()
    {
        return $this->belongsToMany(User::class, 'seance_template_delegates', 'seance_template_id', 'user_id')->withTimestamps();
    }
}
