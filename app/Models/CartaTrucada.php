<?php

namespace App\Models;

use App\Models\Usuari;
use App\Models\Incident;
use App\Models\Municipi;
use App\Models\Expedient;
use App\Models\Provincia;
use App\Models\Interlocutor;
use App\Models\TipusLocalitzacio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaTrucada extends Model
{
    use HasFactory;
    protected $table = 'cartes_trucades';
    protected $guarded = ['id'];

    public function interlocutor()
    {
        return $this->belongsTo(Interlocutor::class, 'interlocutors_id');
    }

    public function tipusLocalitzacio()
    {
        return $this->belongsTo(TipusLocalitzacio::class, 'tipus_localitzacions_id');
    }

    public function municipi()
    {
        return $this->belongsTo(Municipi::class, 'municipis_id');
    }

    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'provincies_id');
    }

    public function incident()
    {
        return $this->belongsTo(Incident::class, 'incidents_id');
    }

    public function expedient()
    {
        return $this->belongsTo(Expedient::class, 'expedients_id');
    }

    public function usuari()
    {
        return $this->belongsTo(Usuari::class, 'usuaris_id');
    }
}
