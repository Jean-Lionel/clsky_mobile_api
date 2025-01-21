<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientHistory extends Model
{
    /** @use HasFactory<\Database\Factories\ClientHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'phone_number' ,
            'full_name',
            'market',
            'province',
            'description',
            'latitude',
            'longitude',
            'client_id',
            'user_id',
            'used'
    ];
}
