<?php

namespace App\Models;

use App\Models\Municipi;
use App\Models\CartaTrucadaHasAgencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agencies extends Model
{
    use HasFactory;

    protected $table = 'agencies';
    public $tiemstamps = false;

     /**
     * Get all of the cartaHasAgencies for the Agencies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartaHasAgencies(): HasMany
    {
        return $this->hasMany(CartaTrucadaHasAgencies::class, 'agencies_id');
    }

    public function municipi()
    {
        return $this->belongsTo(Municipi::class, 'municipis_id');
    }

}
