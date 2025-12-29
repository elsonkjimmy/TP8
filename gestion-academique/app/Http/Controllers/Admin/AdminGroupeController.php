<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Groupe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminGroupeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groupes = Groupe::with('filiere')->get();
        return view('admin.groupes.index', compact('groupes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $filieres = Filiere::all();
        return view('admin.groupes.create', compact('filieres'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
        ]);

        Groupe::create($request->all());

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Groupe $groupe)
    {
        // Not typically used for Groupe management in this context
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Groupe $groupe)
    {
        $filieres = Filiere::all();
        return view('admin.groupes.edit', compact('groupe', 'filieres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Groupe $groupe)
    {
        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
        ]);

        $groupe->update($request->all());

        return redirect()->route('admin.groupes.index')->with('success', 'Groupe mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Groupe $groupe)
    {
        $groupe->delete();
        return redirect()->route('admin.groupes.index')->with('success', 'Groupe supprimé avec succès.');
    }
}