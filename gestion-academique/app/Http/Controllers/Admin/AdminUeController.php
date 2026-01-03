<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Groupe;
use App\Models\Ue;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminUeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Ue::with(['filiere', 'enseignant']);

        if (request('filiere_id')) {
            $query->where('filiere_id', request('filiere_id'));
        }
        if (request('groupe_id')) {
            $query->where('groupe_id', request('groupe_id'));
        }

        $ues = $query->get();

        $filieres = Filiere::all();
        $groupes = Groupe::all();

        return view('admin.ues.index', compact('ues', 'filieres', 'groupes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.ues.create', compact('filieres', 'groupes', 'teachers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:ues'],
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'groupe_id' => ['nullable', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'chapters' => ['nullable','array'],
            'chapters.*' => ['required','string','max:255'],
        ]);

        $ue = Ue::create($request->only(['code','nom','filiere_id','groupe_id','enseignant_id']));

        // Create chapters if provided
        $chapters = $request->input('chapters', []);
        if (!empty($chapters)) {
            $toCreate = [];
            foreach ($chapters as $idx => $title) {
                $title = trim($title);
                if ($title === '') continue;
                $toCreate[] = ['title' => $title, 'position' => $idx + 1];
            }
            if (!empty($toCreate)) {
                $ue->chapters()->createMany($toCreate);
            }
        }

        return redirect()->route('admin.ues.index')->with('success', 'UE créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ue $ue)
    {
        // Not typically used for UE management in this context
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ue $ue)
    {
        $filieres = Filiere::all();
        $groupes = Groupe::all();
        $teachers = User::where('role', 'teacher')->get();
        return view('admin.ues.edit', compact('ue', 'filieres', 'groupes', 'teachers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ue $ue)
    {
        $request->validate([
            'code' => ['required', 'string', 'max:255', Rule::unique('ues')->ignore($ue->id)],
            'nom' => ['required', 'string', 'max:255'],
            'filiere_id' => ['required', 'exists:filieres,id'],
            'groupe_id' => ['nullable', 'exists:groupes,id'],
            'enseignant_id' => ['required', 'exists:users,id'],
            'chapters' => ['nullable','array'],
            'chapters.*' => ['required','string','max:255'],
        ]);

        $ue->update($request->only(['code','nom','filiere_id','groupe_id','enseignant_id']));

        // Sync chapters: simple approach -> delete existing and recreate
        $chapters = $request->input('chapters', []);
        if ($ue->chapters()->exists()) {
            $ue->chapters()->delete();
        }
        $toCreate = [];
        foreach ($chapters as $idx => $title) {
            $title = trim($title);
            if ($title === '') continue;
            $toCreate[] = ['title' => $title, 'position' => $idx + 1];
        }
        if (!empty($toCreate)) {
            $ue->chapters()->createMany($toCreate);
        }

        return redirect()->route('admin.ues.index')->with('success', 'UE mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ue $ue)
    {
        $ue->delete();
        return redirect()->route('admin.ues.index')->with('success', 'UE supprimée avec succès.');
    }
}