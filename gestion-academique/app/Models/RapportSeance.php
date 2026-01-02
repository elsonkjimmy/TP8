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
        'chapter_id',
        'enseignant_id',
        'filled_by_id',
        'validated_by_id',
        'delegue_id',
        'contenu',
        'status',
        // legacy column for older schema
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
     * Get the chapter associated with this report.
     */
    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    /**
     * Get the teacher that created the report.
     */
    public function enseignant()
    {
        return $this->belongsTo(User::class, 'filled_by_id');
    }

    /**
     * Get the delegate that validated the report.
     */
    public function delegue()
    {
        // legacy column `delegue_id` points to delegate who created the report in older schema
        if ($this->delegue_id) {
            return $this->belongsTo(User::class, 'delegue_id');
        }
        return $this->belongsTo(User::class, 'validated_by_id');
    }
}