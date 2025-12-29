<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'expediteur_id',
        'destinataire_id',
        'contenu',
        // Add other fields like 'type', 'read_at' if needed
    ];

    /**
     * Get the user who sent the notification.
     */
    public function expediteur()
    {
        return $this->belongsTo(User::class, 'expediteur_id');
    }

    /**
     * Get the user who received the notification.
     */
    public function destinataireUser()
    {
        return $this->belongsTo(User::class, 'destinataire_id');
    }
}