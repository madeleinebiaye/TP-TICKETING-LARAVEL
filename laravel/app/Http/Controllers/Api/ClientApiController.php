<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    public function index(): JsonResponse
    {
        $clients = Client::orderByDesc('id')
            ->get()
            ->map(fn (Client $client): array => [
                'id'      => $client->id,
                'name'    => $client->name,
                'email'   => $client->email,
                'phone'   => $client->phone,
                'company' => $client->company,
            ]);

        return response()->json($clients);
    }

    public function show(int $id): JsonResponse
    {
        $client = Client::with('projects')->findOrFail($id);

        return response()->json([
            'id'       => $client->id,
            'name'     => $client->name,
            'email'    => $client->email,
            'phone'    => $client->phone,
            'company'  => $client->company,
            'projects' => $client->projects->map(fn ($p) => [
                'id'   => $p->id,
                'name' => $p->name,
            ]),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);

        return response()->json([
            'id'      => $client->id,
            'name'    => $client->name,
            'email'   => $client->email,
            'phone'   => $client->phone,
            'company' => $client->company,
        ], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $client = Client::findOrFail($id);

        $validated = $request->validate([
            'name'    => 'sometimes|required|string|max:255',
            'email'   => 'nullable|email|max:255',
            'phone'   => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return response()->json([
            'id'      => $client->id,
            'name'    => $client->name,
            'email'   => $client->email,
            'phone'   => $client->phone,
            'company' => $client->company,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client supprimé avec succès.']);
    }
}
