<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SeanceTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateDelegateController extends Controller
{
    public function store(Request $request, SeanceTemplate $seanceTemplate)
    {
        // Only the teacher who owns the template (or admin) can modify delegates
        $user = Auth::user();
        if ($seanceTemplate->enseignant_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        $data = $request->validate([
            'delegate_id' => ['required','exists:users,id']
        ]);

        $delegate = User::find($data['delegate_id']);
        $seanceTemplate->delegates()->syncWithoutDetaching([$delegate->id]);

        return redirect()->back()->with('success','Délégué affecté au template.');
    }

    public function destroy(SeanceTemplate $seanceTemplate, $userId)
    {
        $user = Auth::user();
        if ($seanceTemplate->enseignant_id !== $user->id && $user->role !== 'admin') {
            abort(403);
        }

        $seanceTemplate->delegates()->detach($userId);

        return redirect()->back()->with('success','Délégué retiré.');
    }
}
