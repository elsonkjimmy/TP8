<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Desiderata extends Model
{
    use HasFactory;

    protected $fillable = [
        'enseignant_id',
        'seance_template_id',
        'status', // pending, approved, rejected
        'comment',
    ];

    /**
     * Get the teacher who made the request
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'enseignant_id');
    }

    /**
     * Get the seance template being requested
     */
    public function seanceTemplate()
    {
        return $this->belongsTo(SeanceTemplate::class, 'seance_template_id');
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
