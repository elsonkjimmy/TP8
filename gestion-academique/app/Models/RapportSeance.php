<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RapportSeance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'seance_id',
        'enseignant_id',
        'délégué_id',
        'contenu',
        'statut',
    ];

    /**
     * Get the seance that owns the report.
     */
    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    /**
     * Get the teacher that created the report.
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Get the delegate that validated the report.
     */
    public function delegue()
    {
        return $this->belongsTo(User::class, 'délégué_id');
    }
}