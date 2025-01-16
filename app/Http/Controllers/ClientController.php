<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Les administrateurs voient tous les clients
        if ($user->isAdmin()) {
            $clients = Client::with('user')->latest()->paginate();
        } else {
            // Les enquêteurs ne voient que leurs clients
            $clients = Client::with('user')->where('user_id',$user->id )->latest()->paginate();
        }
        
        return response()->json($clients);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'full_name' => 'required|string',
            'market' => 'required|string',
            'province' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $client = auth()->user()->clients()->create($validated);

        return response()->json($client, 201);
    }
    public function show(Client $client)
    {
        $this->authorize('view', $client);
        return response()->json($client);
    }

    public function update(Request $request, Client $client)
    {
        $this->authorize('update', $client);

        $validated = $request->validate([
            'phone_number' => 'required|string',
            'full_name' => 'required|string',
            'market' => 'required|string',
            'province' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        $client->delete();
        return response()->json(null, 204);
    }
}