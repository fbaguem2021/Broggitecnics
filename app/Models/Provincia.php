<?php

namespace App\Models;

use App\Models\Comarca;
use App\Models\CartaTrucada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provincia extends Model
{
    use HasFactory;
    protected $table = 'provincies';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }

    public function comarques()
    {
        return $this->hasMany(Comarca::class, 'provincies_id');
    }

}
