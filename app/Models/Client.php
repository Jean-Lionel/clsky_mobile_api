<?php

namespace App\Models;
use App\Models\ClientHistory;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'phone_number',
        'full_name',
        'market',
        'province',
        'description',
        'latitude',
        'longitude',
        'user_id'
    ];
    protected $appends = ['totalModifications'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clientHistory(){

        return $this->hasMany(ClientHistory::class)->whereNull('used');
    }

    public function getTotalModificationsAttribute(){
        return $this->clientHistory->count();
    }
}