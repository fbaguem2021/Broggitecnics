<?php

namespace App\Models;


use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuari extends Authenticatable
{
    use HasApiTokens,HasFactory, Notifiable;

    protected $table = 'usuaris';
    public $timestamps = false;

    public function rol(): BelongsTo{
        return $this->belongsTo(Rol::class, 'tipus_usuaris_id');
    }
}
