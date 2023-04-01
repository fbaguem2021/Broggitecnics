<?php

namespace App\Models;

use App\Models\Agencies;
use App\Models\CartaTrucada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EstatAgencia extends Model
{
    use HasFactory;

    protected $table = 'estat_agencies';
    public $timestamps = false;

    public function agencies()
    {
        return $this->hasMany(Agencies::class);
    }

    public function cartesTrucades()
    {
        return $this->hasManyThrough(CartaTrucada::class, Agencies::class);
    }


}
