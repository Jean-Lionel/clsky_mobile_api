<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        // Liste des provinces
        $provinces = [
            'Kinshasa',
            'Kongo Central',
            'Kwango',
            'Kwilu',
            'Mai-Ndombe',
            'Equateur',
            'Mongala',
            'Nord-Ubangi',
            'Sud-Ubangi',
            'Tshuapa'
        ];

        // Liste des marchés
        $markets = [
            'Marché Central',
            'Marché de Gros',
            'Marché aux Légumes',
            'Marché Artisanal',
            'Marché aux Poissons'
        ];

        // Récupérer tous les enquêteurs
        $enqueteurs = User::where('role', UserRole::ENQUETEUR)->get();

        foreach ($enqueteurs as $enqueteur) {
            // Créer 20 clients pour chaque enquêteur
            for ($i = 1; $i <= 20; $i++) {
                Client::create([
                    'phone_number' => '+243' . rand(800000000, 899999999),
                    'full_name' => "Client $i de " . $enqueteur->name,
                    'market' => $markets[array_rand($markets)],
                    'province' => $provinces[array_rand($provinces)],
                    'description' => "Description du client numéro $i. Ce client est enregistré par " . $enqueteur->name,
                    'latitude' => rand(-4, 4) + (rand(0, 1000000) / 1000000),
                    'longitude' => rand(15, 25) + (rand(0, 1000000) / 1000000),
                    'user_id' => $enqueteur->id
                ]);
            }
        }
    }
}