<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'code',
        'enseignant_responsable_id',
    ];

    /**
     * Get the user that is the responsable teacher for the filiere.
     */
    public function enseignantResponsable()
    {
        return $this->belongsTo(User::class, 'enseignant_responsable_id');
    }

    /**
     * Get all UEs for this filiere.
     */
    public function ues()
    {
        return $this->hasMany(Ue::class);
    }

    /**
     * Get all groupes for this filiere.
     */
    public function groupes()
    {
        return $this->hasMany(Groupe::class);
    }
}