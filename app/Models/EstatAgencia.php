<?php

namespace App\Models;

use App\Models\CartaTrucadaHasAgencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstatAgencia extends Model
{
    use HasFactory;

    protected $table = 'estat_agencies';
    public $timestamps = false;

    /**
     * Get all of the CartesHasAgencies for the EstatAgencia
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function CartesHasAgencies(): HasMany
    {
        return $this->hasMany(CartaTrucadaHasAgencies::class, 'estat_agencies_id');
    }

}
