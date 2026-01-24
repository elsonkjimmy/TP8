<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemandeModification extends Model
{
    protected $table = 'demandes_modifications';

    protected $fillable = [
        'enseignant_id',
        'seance_id',
        'seance_template_id',
        'type_demande',
        'description',
        'status',
        'admin_response',
        'admin_id',
    ];

    /**
     * Get the teacher who made the request
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Get the seance being requested to modify
     */
    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    /**
     * Get the seance template being requested to modify
     */
    public function seanceTemplate()
    {
        return $this->belongsTo(SeanceTemplate::class, 'seance_template_id');
    }

    /**
     * Get the admin who responded
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'soumis');
    }

    /**
     * Scope for accepted requests
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepté');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejeté');
    }
}
