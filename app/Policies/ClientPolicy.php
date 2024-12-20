<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    public function view(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }

    public function update(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }

    public function delete(User $user, Client $client)
    {
        return $user->id === $client->user_id;
    }
}