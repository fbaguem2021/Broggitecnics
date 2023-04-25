<?php

namespace App\Models;

use App\Models\CartaTrucada;
use App\Models\TipusIncident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Incident extends Model
{
    use HasFactory;

    protected $table = 'incidents';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }

    public function tipusIncidents () {
        return $this->belongsTo(TipusIncident::class, 'tipus_incidents_id');
    }
}
