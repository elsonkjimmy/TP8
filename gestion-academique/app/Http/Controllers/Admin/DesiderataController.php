<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Desiderata;
use App\Models\SeanceTemplate;
use Illuminate\Http\Request;

class DesiderataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $desideratas = Desiderata::with(['enseignant', 'seanceTemplate.ue', 'seanceTemplate.groupe'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('admin.desideratas.index', compact('desideratas'));
    }

    /**
     * Approve a desiderata.
     */
    public function accept(Desiderata $desiderata)
    {
        // 1. Assign teacher to the SeanceTemplate
        $template = $desiderata->seanceTemplate;
        
        // Check if template already has a teacher? Or overwrite?
        // Assuming overwrite or fill blank.
        $template->enseignant_id = $desiderata->enseignant_id;
        $template->save();

        // 2. Update status
        $desiderata->update(['status' => 'approved']);

        // 3. Reject conflicting desideratas for same template?
        // Optional: auto-reject others for same template
        Desiderata::where('seance_template_id', $desiderata->seance_template_id)
            ->where('id', '!=', $desiderata->id)
            ->where('status', 'pending')
            ->update(['status' => 'rejected', 'comment' => 'Auto-rejected due to approval of another request.']);

        return back()->with('success', 'Désirata accepté et enseignant assigné.');
    }

    /**
     * Reject a desiderata.
     */
    public function reject(Request $request, Desiderata $desiderata)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string|max:255',
        ]);

        $desiderata->update([
            'status' => 'rejected',
            'comment' => $validated['comment'] ?? $desiderata->comment,
        ]);

        return back()->with('success', 'Désirata rejeté.');
    }
}
