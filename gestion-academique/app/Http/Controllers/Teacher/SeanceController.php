<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Seance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeanceController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $user = Auth::user();

        if ($seance->enseignant_id !== $user->id && ! $user->hasRole('admin')) {
            abort(403);
        }

        $request->validate(['status' => 'required|in:scheduled,completed,cancelled,reported,missed']);

        $seance->status = $request->input('status');
        $seance->save();

        return back()->with('success', 'Statut mis à jour.');
    }

    public function assignDelegate(Request $request, $id)
    {
        $seance = Seance::findOrFail($id);
        $user = Auth::user();

        if ($seance->enseignant_id !== $user->id) abort(403);

        $request->validate(['delegate_id' => 'required|exists:users,id']);

        $delegate = User::find($request->input('delegate_id'));
        $seance->delegates()->syncWithoutDetaching([$delegate->id]);

        return back()->with('success', 'Délégué assigné.');
    }
}
