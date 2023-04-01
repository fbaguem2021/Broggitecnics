<?php

namespace App\Models;

use App\Models\Comarca;
use App\Models\Agencies;
use App\Models\CartaTrucada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Municipi extends Model
{
    use HasFactory;

    protected $table = 'municipis';
    public $timestamps = false;

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }

    public function comarca ()
    {
        return $this->belongsTo(Comarca::class, 'comarques_id');
    }
    
    public function agencies ()
    {
        return $this->hasMany(Agencies::class);
    }

}
