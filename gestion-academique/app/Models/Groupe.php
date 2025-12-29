<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groupe extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'filiere_id',
    ];

    /**
     * Get the filiere that owns the Groupe.
     */
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }
}