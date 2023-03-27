<?php

namespace App\Models;

use App\Models\Usuari;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'tipus_usuaris';
    public $timestamps = false;

    public function usuaris(): HasMany{
        return $this->hasMany(Usuari::class, 'tipus_usuaris_id');
    }
}
