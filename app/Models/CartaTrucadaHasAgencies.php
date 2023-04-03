<?php

namespace App\Models;

use App\Models\Agencies;
use App\Models\CartaTrucada;
use App\Models\EstatAgencia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartaTrucadaHasAgencies extends Model
{
    use HasFactory;

    protected $table = 'cartes_trucades_has_agencies';
    protected $primaryKey = ['cartes_trucades_id', 'agencies_id'];
    public $incrementing = false;
    public $timestamps = false;

    /**
     * Get the carta that owns the CartaTrucadaHasAgencies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carta(): BelongsTo
    {
        return $this->belongsTo(CartaTrucada::class, 'cartes_trucades_id');
    }

    /**
     * Get the agencia that owns the CartaTrucadaHasAgencies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agencia(): BelongsTo
    {
        return $this->belongsTo(Agencies::class, 'agencies_id');
    }

    /**
     * Get the estatAgencia that owns the CartaTrucadaHasAgencies
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function estatAgencia(): BelongsTo
    {
        return $this->belongsTo(EstatAgencia::class, 'estat_agencies_id');
    }
}
