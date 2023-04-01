<?php

namespace App\Models;

use App\Models\Municipi;
use App\Models\CartaTrucada;
use App\Models\EstatAgencia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agencies extends Model
{
    use HasFactory;

    protected $table = 'agencies';
    public $tiemstamps = false;

    public function cartesTrucades()
    {
        return $this->belongsToMany(CartaTrucada::class, 'cartes_trucades_has_agencies', 'agencies_id', 'cartes_trucades_id')
            ->withPivot('estat_agencies_id');
    }

    public function estatAgencies()
    {
        return $this->belongsTo(EstatAgencia::class);
    }

    public function municipi()
    {
        return $this->belongsTo(Municipi::class, 'municipis_id');
    }

}
