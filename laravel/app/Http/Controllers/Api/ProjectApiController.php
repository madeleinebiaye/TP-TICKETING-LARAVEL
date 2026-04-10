<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectApiController extends Controller
{
    public function index(): JsonResponse
    {
        $projects = Project::with('client')
            ->orderByDesc('id')
            ->get()
            ->map(fn (Project $project): array => [
                'id'          => $project->id,
                'name'        => $project->name,
                'description' => $project->description,
                'client_id'   => $project->client_id,
                'client_name' => $project->client?->name,
                'created_at'  => $project->created_at,
            ]);

        return response()->json($projects);
    }

    public function show(int $id): JsonResponse
    {
        $project = Project::with('client', 'tickets')->findOrFail($id);

        return response()->json([
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'client_id'   => $project->client_id,
            'client_name' => $project->client?->name,
            'tickets'     => $project->tickets->map(fn ($t) => [
                'id'     => $t->id,
                'title'  => $t->title,
                'status' => $t->status,
            ]),
            'created_at'  => $project->created_at,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'client_id'   => 'nullable|exists:clients,id',
        ]);

        $project = Project::create($validated);
        $project->load('client');

        return response()->json([
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'client_id'   => $project->client_id,
            'client_name' => $project->client?->name,
            'created_at'  => $project->created_at,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'name'        => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'client_id'   => 'nullable|exists:clients,id',
        ]);

        $project->update($validated);
        $project->load('client');

        return response()->json([
            'id'          => $project->id,
            'name'        => $project->name,
            'description' => $project->description,
            'client_id'   => $project->client_id,
            'client_name' => $project->client?->name,
            'created_at'  => $project->created_at,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return response()->json(['message' => 'Projet supprimé avec succès.']);
    }
}
