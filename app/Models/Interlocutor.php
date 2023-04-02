<?php

namespace App\Models;

use App\Models\CartaTrucada;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interlocutor extends Model
{
    use HasFactory;

    protected $table = 'interlocutors';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function cartesTrucades()
    {
        return $this->hasMany(CartaTrucada::class);
    }
}
