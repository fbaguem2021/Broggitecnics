<?php

namespace App\Models;

use App\Models\CartaTrucada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipusLocalitzacio extends Model
{
    use HasFactory;

    protected $table = 'tipus_localitzacions';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }
}
