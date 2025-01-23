<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientHistory;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $clients = [];
        // Les administrateurs voient tous les clients
        if ($user->isAdmin()) {
            $clients = Client::with(['user','clientHistory'])
            
            ->latest()->paginate();
        } else {
            // Les enquêteurs ne voient que leurs clients
            $clients = Client::with(['user','clientHistory'])->where('user_id',$user->id )->latest()->paginate();
        } 
        return response()->json($clients);
    }
    

    public function accepte($id){

        try{
            $history = ClientHistory::find($id);
            $client = Client::find( $history->client_id);
            $history->used = "ACCEPTED";
            $history->save();

        }catch(\Exception $e){
            return $e->getMessage();
        }
        return response()->json([
            "message" => "ACCEPTED"
        ]);
        
    }
    public function refuse($id){

        try{
            $history = ClientHistory::find($id);
            $client = Client::find( $history->client_id);
            $client->update( $history->toArray());
            $history->used = "REFUSE";
            $history->save();
        }catch(\Exception $e){
            return $e->getMessage();
        }
        return response()->json([
            "message" => "REFUSE"
        ]);
    }
    public function report(Request $request){

      
        
        // Initialisation de la requête de base
        $query = Client::with('user');
        // Application de filtres conditionnels basés sur les paramètres de la requête
     
        if ($request->has('name')) {
            $query->where('full_name', 'like', '%' . $request->name . '%');
        }
        if ($request->has('phone_number')) {
            $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        }
        if ($request->has('market')) {
            $query->where('market', 'like', '%' . $request->market . '%');
        }
        if ($request->has('province')) {
            $query->where('province', 'like', '%' . $request->province . '%');
        }
        if ($request->has('description')) {
            $query->where('description', 'like', '%' . $request->description . '%');
        }
        if ($request->has('latitude')) {
            $query->where('latitude', $request->latitude);
        }
        if ($request->has('longitude')) {
            $query->where('longitude', $request->longitude);
        }
        
        if ($request->has('user_id') && intVal($request->user_id) ) {
            $query->where('user_id', $request->user_id);
        }
        // Récupérer les clients avec tri par ordre décroissant
        $clients = $query->latest()->get();
        
        // Retourner les clients sous forme de réponse JSON
        return response()->json($clients);
    }
    
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'required|string|unique:clients,phone_number',
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
       // $this->authorize('view', $client);
        return response()->json($client);
    }
    
    public function update(Request $request, Client $client)
    {
      //  $this->authorize('update', $client);
        
        $validated = $request->validate([
            'phone_number' => 'required|string',
            'full_name' => 'required|string',
            'market' => 'required|string',
            'province' => 'required|string',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);
       $client_history =  ClientHistory::create(attributes:
        
        array_merge($client->toArray() , [
            'client_id' => $client->id,
            'user_id' => auth()->user()->id,
        ],

        )
    );
        
    $client->update($validated);
        
        return response()->json(data: $client_history);
    }
    
    public function destroy(Client $client)
    {
        $this->authorize('delete', $client);
        $client->delete();
        return response()->json(null, 204);
    }
}