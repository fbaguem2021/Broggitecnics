<?php

namespace App\Models;

use App\Models\CartaTrucada;
use App\Models\EstatExpedient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expedient extends Model
{
    use HasFactory;

    protected $table = 'expedients';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function estatExpedient()
    {
        return $this->belongsTo(EstatExpedient::class, 'estat_expedients_id');
    }

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }
}
