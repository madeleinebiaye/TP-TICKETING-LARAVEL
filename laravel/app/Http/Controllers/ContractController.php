<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function edit(int $projectId): View
    {
        $project = Project::with('contract')->findOrFail($projectId);

        return view('contracts.edit', compact('project'));
    }

    public function update(Request $request, int $projectId): RedirectResponse
    {
        $project = Project::with('contract')->findOrFail($projectId);

        $validated = $request->validate([
            'included_hours' => 'required|integer|min:0',
            'hourly_rate' => 'required|numeric|min:0',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        $project->contract()->updateOrCreate(
            ['project_id' => $project->id],
            $validated
        );

        return redirect('/projects/'.$project->id)
            ->with('success', 'Contrat mis à jour avec succès.');
    }
}
