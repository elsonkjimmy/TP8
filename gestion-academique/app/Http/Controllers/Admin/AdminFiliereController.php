<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\User; // Don't forget to import User model
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminFiliereController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filieres = Filiere::with('enseignantResponsable')->get();
        return view('admin.filieres.index', compact('filieres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.filieres.create', compact('teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', 'unique:filieres'],
            'enseignant_responsable_id' => ['required', 'exists:users,id'],
        ]);

        Filiere::create($request->all());

        return redirect()->route('admin.filieres.index')->with('success', 'Filière créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Filiere $filiere)
    {
        // Not typically used for filiere management in this context
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Filiere $filiere)
    {
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.filieres.edit', compact('filiere', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Filiere $filiere)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:255', Rule::unique('filieres')->ignore($filiere->id)],
            'enseignant_responsable_id' => ['required', 'exists:users,id'],
        ]);

        $filiere->update($request->all());

        return redirect()->route('admin.filieres.index')->with('success', 'Filière mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Filiere $filiere)
    {
        $filiere->delete();
        return redirect()->route('admin.filieres.index')->with('success', 'Filière supprimée avec succès.');
    }
}