<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = [
        'ue_id',
        'title',
        'description',
        'position',
    ];

    public function ue()
    {
        return $this->belongsTo(Ue::class);
    }
}
