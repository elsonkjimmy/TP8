<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Notification; // Assuming you have a Notification model
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notifications = Notification::with(['expediteur', 'destinataireUser'])->get(); // Assuming relationships
        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $teachersAndDelegates = User::whereIn('role', ['teacher', 'delegate'])->get();
        return view('admin.notifications.create', compact('filieres', 'groupes', 'teachersAndDelegates'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recipient_type' => ['required', 'string', Rule::in(['filiere', 'groupe', 'user'])],
            'recipient_id' => ['required', 'integer'],
            'contenu' => ['required', 'string'],
        ]);

        // Logic to determine actual recipient_id based on type
        $destinataireId = null;
        if ($validated['recipient_type'] === 'user') {
            $destinataireId = $validated['recipient_id'];
        }
        // For filiere and groupe, you might store the filiere_id or groupe_id in a separate column
        // or handle it differently based on your Notification model structure.
        // For simplicity, let's assume for now it's always a user.
        // If you need to send to all users in a filiere/groupe, you'd fetch them here.

        Notification::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $destinataireId, // This needs to be dynamic based on recipient_type
            'contenu' => $validated['contenu'],
            // Add other fields like type, read_at etc.
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notification envoyée avec succès.');
    }
}