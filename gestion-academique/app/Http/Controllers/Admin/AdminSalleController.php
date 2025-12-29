<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminSalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $salles = Salle::all();
        return view('admin.salles.index', compact('salles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.salles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero' => ['required', 'string', 'max:255', 'unique:salles'],
            'capacite' => ['required', 'integer', 'min:1'],
        ]);

        Salle::create($request->all());

        return redirect()->route('admin.salles.index')->with('success', 'Salle créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Salle $salle)
    {
        // Not typically used for Salle management in this context
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salle $salle)
    {
        return view('admin.salles.edit', compact('salle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salle $salle)
    {
        $request->validate([
            'numero' => ['required', 'string', 'max:255', Rule::unique('salles')->ignore($salle->id)],
            'capacite' => ['required', 'integer', 'min:1'],
        ]);

        $salle->update($request->all());

        return redirect()->route('admin.salles.index')->with('success', 'Salle mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salle $salle)
    {
        $salle->delete();
        return redirect()->route('admin.salles.index')->with('success', 'Salle supprimée avec succès.');
    }
}